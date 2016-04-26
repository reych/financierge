<?php
include("vendor/autoload.php");
include("Transaction.php");
use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;

date_default_timezone_set("America/Los_Angeles");
session_start();
ParseClient::initialize("9DwkUswTSJOLVi7dkRJxDQNbwHSDlQx3NTdXz5B0", "6HFMDcw8aRr9O7TJ3Pw8YOWbecrdiMuAPEL3OXia", "IdmvCVEBYygkFTRmxOwUvSxtnXwlaGDF9ndq5URq");

class Network {

	// Network::loginUser(username: string, password: string)
	// returns ParseUser or NULL
	static function loginUser($username, $password) {
		try {
			$user = ParseUser::logIn($username, $password);
			return $user;
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// Network::getCurrentUser()
	// returns ParseUser or NULL
	static function getCurrentUser() {
		return ParseUser::getCurrentUser();
	}

	// Network::logoutUser()
	// returns ParseUser or NULL
	static function logoutUser() {
		ParseUser::logOut();
	}

	// Network::addAccount(name: string, isAsset: bool)
	// returns true or false
	static function addAccount($name, $isAsset) {
		try {
			// creates an account and saves it in the Account table on Parse
			$account = new ParseObject("Account");
			$account->set("name", $name);
			$account->set("balance", 0);
			$account->set("isAsset", $isAsset);
			$account->save();
			// adds the account to the accounts array for the user and saves it in the User table on Parse
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				$accounts[] = $account;
				$currentUser->setArray("accounts", $accounts);
				$currentUser->save();
				return true;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// Network::getAccounts()
	// returns array of Parse objects or NULL
	static function getAccounts() {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					// uncomment the line below to see which accounts were fetched
					// echo $accounts[$i]->get("name") . "\n";
				}
				return $accounts;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// Network::deleteAccount(name: string)
	// returns true or false
	static function deleteAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						for ($k = 0; $k < count($transactions); $k++) {
							// deletes each transaction for the account in the Transaction table on Parse
							$transactions[$k]->destroy();
						}
						// deletes the account in the Account table on Parse
						$accounts[$i]->destroy();
						unset($accounts[$i]);
						$currentUser->setArray("accounts", $accounts);
						$currentUser->save();
						return true;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// Network::addTransactionToAccount(name: string, date: DateTime, principe: string, amount: number, category: string, isAsset: bool)
	// returns true or false
	static function addTransactionToAccount($name, $date, $principle, $amount, $category, $isAsset) {
		try {
			// creates a transaction and saves it in the Transaction table on Parse
			$transaction = new ParseObject("Transaction");
			$transaction->set("date", $date);
			$transaction->set("principle", $principle);
			$transaction->set("amount", $amount);
			$transaction->set("category", $category);
			$transaction->set("isAnAsset", $isAsset);
			$transaction->save();
			// adds the transaction to the account in the accounts array for the user and saves it in the User table on Parse
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						$transactions[] = $transaction;
						$accounts[$i]->setArray("transactions", $transactions);
						$currentUser->setArray("accounts", $accounts);
						$currentUser->save();
						return true;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	// take in an associative array with account names as keys and all transactions to be added to that particular account as an array as the value
	static function addTransactionsToAccounts($newTransactions) {
		$transactionsByCategory = array();

		try {

			// add transaction to specified account in accounts array for current user and save it in User table
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				// get all accounts for current user from parse
				$accounts = $currentUser->get("accounts");

				// for each account that the user has
				for ($i = 0; $i < count($accounts); $i++) {
					$accountQuery = new ParseQuery("Account");
					// get the actual account (just had reference, not actual object)
					// $actualAccount = $accountQuery->get($accounts[$i]);//->fetch();
					$accounts[$i]->fetch();

					// get the name of this particular account
					$thisAccountName = $accounts[$i]->get("name");
					// $thisAccountName = $actualAccount->get("name");

					// if the account name matches a key in newTransactions, then we have some transactions to add to this account
					if (array_key_exists($thisAccountName, $newTransactions)) {
						// an array of new transaction objects (from model file) for this particular account
						$newTransForAccount = $newTransactions[$thisAccountName];

						$currentAccountTransactions = $accounts[$i]->get("transactions");
						// $currentAccountTransactions = $actualAccount->get("transactions")

						// for each new transaction for this account, add it to the account
						for ($j = 0; $j < count($newTransForAccount); $j++) {

							// echo "Trying to add transaction: ".$newTransForAccount[$j]->$principle.PHP_EOL;

							// create new parse Transaction object
							$transaction = new ParseObject("Transaction");
							$transaction->set("date", $newTransForAccount[$j]->date);
							$transaction->set("principle", $newTransForAccount[$j]->principle);
							$transaction->set("amount", $newTransForAccount[$j]->amount);
							$transaction->set("category", $newTransForAccount[$j]->category);
							$transaction->set("isAnAsset", $newTransForAccount[$j]->isAsset);
							$transaction->save();

							$currentAccountTransactions[] = $transaction;

							$ctgry = $newTransForAccount[$j]->category;
							if (array_key_exists($ctgry, $transactionsByCategory)) {
								// add it
								$transactionsByCategory[$ctgry] = $transaction;

							} else {

								$transactionsByCategory[$ctgry] = array();
								// add transaction to asset array
								$transactionsByCategory[$ctgry] = $transaction;
							}
						}

						$accounts[$i]->setArray("transactions", $currentAccountTransactions);
						// $actualAccount->setArray("transactions", $currentAccountTransactions);
					}
				}


				$currentUser->setArray("accounts", $accounts);

				// somewhere down here we need to go through the
				// array holding catagory names as keys and arrays
				// of parse transaction objects as values and add
				// them to the appropriate place in parse
				Network::addTransactionsToCategories($transactionsByCategory);
				$currentUser->save();
				return true;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}


	// Network::getTransactionsForAccount(name: string)
	// returns array of Parse objects or NULL
	static function getTransactionsForAccount($name) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						for ($k = 0; $k < count($transactions); $k++) {
							// must call fetch in order to convert the transaction from Parse pointer to Parse object
							$transactions[$k]->fetch();
						}
						return $transactions;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// Network::getTransactionsForAccountWithinDates(name: string, start: DateTime, end: DateTime, sort: string)
	// returns array of Parse objects or NULL
	static function getTransactionsForAccountWithinDates($name, $start, $end, $sort) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$accounts = $currentUser->get("accounts");
				for ($i = 0; $i < count($accounts); $i++) {
					// must call fetch in order to convert the account from Parse pointer to Parse object
					$accounts[$i]->fetch();
					if (strcmp($accounts[$i]->get("name"), $name) == 0) {
						$transactions = $accounts[$i]->get("transactions");
						$transactionIDs = [];
						// stores the object ID for each transaction
						for ($k = 0; $k < count($transactions); $k++) {
							$transactionIDs[] = $transactions[$k]->getObjectId();
						}
						// creates a transaction query for fetching transactions that match the criteria below
						$transactionQuery = new ParseQuery("Transaction");
						$transactionQuery->greaterThanOrEqualTo("date", $start);
						$transactionQuery->lessThanOrEqualTo("date", $end);
						$transactionQuery->containedIn("objectId", $transactionIDs);
						// determines how the transactions should be sorted once fetched
						$amt = strlen("amount");
						$dt = strlen("date");
    					if ((substr($sort, 0, $amt) === "amount") || (substr($sort, 0, $dt) === "date")) {
    						$transactionQuery->descending($sort);
    					} else {
							$transactionQuery->ascending($sort);
						}
						$transactions = $transactionQuery->find();
						return $transactions;
					}
				}
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return NULL;
	}

	// this function takes in an array that maps category names as keys to arrays
	// of Transaction objects as values.
	static function addTransactionsToCategories($transactionsByCategory) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$categories = $currentUser->get("categories");
				if (!isset($categories)) {
					$currentUser->setAssociativeArray("categories", $transactionsByCategory);
				} else {
					foreach ($transactionsByCategory as $category => $transactions) {
						if (array_key_exists($category, $categories)) {
							$currentTransactions = $categories[$category];
							for ($i = 0; $i < count($transactions); $i++) {
								$currentTransactions[] = $transactions[$i];
							}
							$categories[$category] = $currentTransactions;
						} else {
							$categories = array();
							$categories[$category] = $transactions;
						}
					}
					$currentUser->setAssociativeArray("categories", $categories);
				}
				//$currentUser->save();
				return true;
			}
		} catch (ParseException $error) {
			echo "hello";
			echo $error->getMessage();
		}
		return false;
	}

	//returns the budget amount from the budget table by category name AND month/year
	//if the particular row is not in the table (database) return 0
	static function getAmountForBudget($categoryName, $monthYear) {
		// return 100;
		try {
			$currentUser = ParseUser::getCurrentUser();
			// $budgets = $currentUser->get("budgets");
			// $budgetIDs = [];
			// foreach ($budgets as $budget) {
			// 	$budgetIDs[] = $budget->getObjectId();
			// }
			// $budgetQuery = new ParseQuery("Budget");
			// $budgetQuery->equalTo("month", $monthYear);
			// $budgetQuery->containedIn("objectID", $budgetIDs);
			// $budget = $budgetQuery->find();
			// //$val = $budget[0]->get("amount");
			// if ($budget) {

			// 	return 150;//$val;
			// }
			// return 0;
			if ($currentUser) {
				$budgets = $currentUser->get("budgets");
				// echo print_r($budgets) . "asdfasf ";
				// echo count($budgets) . " ";
				for ($i = 0; $i < count($budgets); $i++) {
					$budgets[$i]->fetch();
					if (strcmp($budgets[$i]->get("category"), $categoryName) == 0) {
						// echo "cat equal \n";
						$month = $budgets[$i]->get("month");
						// echo $budgets[$i]->get("amount");
						// echo $month->format('Y-m');
						// echo $monthYear;
						if ($month->format('Y-m') == $monthYear) {
							$amount = $budgets[$i]->get("amount");
							// echo $amount;
							return $amount;
						}
						// $monthYear = getdate($monthYear);
						// $month = getdate($month);  // uncomment if function does not work
						// if ($month["mon"] == $monthYear["mon"] && $month["year"] == $monthYear["year"]) {
						// if ($month->format('Y-m-d') == $monthYear) {
						// 	$amount = $budgets[$i]->get("amount");
						// 	echo "   " . $amount;
						// 	return $amount;
						// }

						// return "failed" . $month->format('Y-m-d H:i:s') . PHP_EOL . $monthYear->format('Y-m-d H:i:s');

					}
				}
			}
		} catch (ParseException $error) {
			echo "error cauth: " . $error->getMessage();
		}
		return 0;
	}

	//returns an array of transaction objects from the transactions_by_category table
	//within the dates provided. If no transactions for the given categoryName or dates,
	//return NULL
	static function getTransactionsForCategoryWithinDates($name, $start, $end) {
		try {
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$categories = $currentUser->get("categories");
				foreach ($categories as $category => $transactions) {
					if (strcmp($category, $name) == 0) {
						$transactionIDs = [];
						for ($i = 0; $i < count($transactions); $i++) {
							$transactionIDs[] = $transactions[$i]->getObjectId();
						}
						$transactionQuery = new ParseQuery("Transaction");
						$transactionQuery->greaterThanOrEqualTo("date", $start);
						$transactionQuery->lessThanOrEqualTo("date", $end);
						$transactionQuery->containedIn("objectId", $transactionIDs);
						$transactionQuery->descending("date");
						$transactions = $transactionQuery->find();
						// UNCOMMENT BELOW TO TEST
						echo $category . "\n";
						for ($i = 0; $i < count($transactions); $i++) {
							echo $transactions[$i]->get("principle") . " -> " . $transactions[$i]->get("date")->format("Y-m-d");
						}
						return $transactions;
					}
				}
				return true;
			}
		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}

	//creates new row in budget table with giving information
	static function addBudget($categoryName, $monthYear, $newBudget) {
		try {

			//find out if this category/date combination exists for a budget in parse
			// if it does, set it's new amount.
			$inputDateString = date_format($monthYear, 'Y-m-d');
			$currentUser = ParseUser::getCurrentUser();
			if ($currentUser) {
				$budgets = $currentUser->get("budgets");
				for ($i = 0; $i < count($budgets); $i++) {
					$budgets[$i]->fetch();
					$date = $budgets[$i]->get("month");
					$name = $budgets[$i]->get("category");
					
					$thisDateString = date_format($date, 'Y-m-d');
					if (substr($categoryName, 0, strlen($name)) === $name 
						&& substr($inputDateString, 0, strlen($thisDateString)) === $thisDateString) {

						$budgets[$i]->set("amount", $newBudget);
						$currentUser->save();
						return true;
					}
				}
			}
			// if it doesn't, do what's below:

			// creates an account and saves it in the Account table on Parse

			// adds the account to the accounts array for the user and saves it in the User table on Parse
			if ($currentUser) {
				// echo $categoryName . " " . $monthYear->format('Y-m') . " ";
				$budgets = $currentUser->get("budgets");
				// echo count($budgets) . " ";
				// echo print_r($budgets);
				if ($budgets) {
					foreach ($budgets as $singleBudget) {
						$singleBudget->fetch();
						// echo $singleBudget->get("category") . " " . $singleBudget->get("month")->format('Y-m');
						if (strcmp($singleBudget->get("category"), $categoryName) == 0) {
							if ($singleBudget->get("month")->format('Y-m') == $monthYear->format('Y-m')) {
								$singleBudget->set("amount", $newBudget);
								$singleBudget->save();
								return true;
							}
						}
					}
				}

				$budget = new ParseObject("Budget");
				$budget->set("category", $categoryName);
				$budget->set("month", $monthYear);
				$budget->set("amount", $newBudget);
				$budget->save();
				$budgets[] = $budget;
				$currentUser->setArray("budgets", $budgets);
				$currentUser->save();

			}

		} catch (ParseException $error) {
			echo $error->getMessage();
		}
		return false;
	}
}

// UNCOMMENT THE LINES BELOW TO TEST
// Network::loginUser("christdv@usc.edu", "christdv");
// $transaction1 = new ParseObject("Transaction");
// $transaction1->set("date", new DateTime("2016-03-30"));
// $transaction1->set("principle", "TEST 1");
// $transaction1->set("amount", 1024);
// $transaction1->set("category", "food");
// $transaction1->set("isAnAsset", true);
// $transaction2 = new ParseObject("Transaction");
// $transaction2->set("date", new DateTime("2016-02-30"));
// $transaction2->set("principle", "TEST 2");
// $transaction2->set("amount", 1024);
// $transaction2->set("category", "food");
// $transaction2->set("isAnAsset", true);
// $transaction3 = new ParseObject("Transaction");
// $transaction3->set("date", new DateTime("2016-04-30"));
// $transaction3->set("principle", "TEST 3");
// $transaction3->set("amount", 1024);
// $transaction3->set("category", "food");
// $transaction3->set("isAnAsset", true);
// $trans = array($transaction1, $transaction2, $transaction3);
// $arr = array("Checkinggggg" => $trans);
// Network::addTransactionsToAccounts($arr);
// $start = new DateTime("2016-02-01");
// $end = new DateTime("2016-02-30");
// Network::getTransactionsForCategoryWithinDates("food", $start, $end);
// Network::logoutUser();

// Network::loginUser("renachen@usc.edu", "rc");
// $start = new DateTime("2016-02-01");
// $end = new DateTime("2016-02-30");
// //$transactions = Network::getTransactionsForAccountWithinDates("Checking", $start, $end, "date");
// $transactions = Network::getTransactionsForCategoryWithinDates("food", $start, $end);
// for ($i = 0; $i < count($transactions); $i++) {
// 	echo $transactions[$i]->get("principle") . " -> " . $transactions[$i]->get("date")->format("Y-m-d") . "\n";
// }
?>
