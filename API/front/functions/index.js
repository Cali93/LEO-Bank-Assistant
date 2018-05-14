// const functions = require('firebase-functions');

// // Create and Deploy Your First Cloud Functions
// // https://firebase.google.com/docs/functions/write-firebase-functions
//
// exports.helloWorld = functions.https.onRequest((request, response) => {
//  response.send("Hello from Firebase!");
// });
'use strict';

process.env.DEBUG = 'actions-on-google:*';
const App = require('actions-on-google').DialogflowApp;
const functions = require('firebase-functions');
const request = require('request');

// a. the action name from the make_name Dialogflow intent
const MAKE_ACTION = 'make_name';
// b. the parameters that are parsed from the make_name intent
const COLOR_ARGUMENT = 'color';
const NUMBER_ARGUMENT = 'number';


exports.sillyNameMaker = functions.https.onRequest((request, response) => {
  const app = new App({request, response});
  console.log('Request headers: ' + JSON.stringify(request.headers));
  console.log('Request body: ' + JSON.stringify(request.body));


// c. The function that generates the silly name
  function makeName (app) {
    let number = app.getArgument(NUMBER_ARGUMENT);
    let color = app.getArgument(COLOR_ARGUMENT);
    app.tell('Alright, your silly name is ' +
      color + ' ' + number +
      '! I hope you like it. See you next time.');
  }
  // d. build an action map, which maps intent names to functions
  let actionMap = new Map();
  actionMap.set(MAKE_ACTION, makeName);


app.handleRequest(actionMap);
});

// a. the action name from the make_name Dialogflow intent
const BALANCE_ACTION = 'account_balance_check';
// b. the parameters that are parsed from the make_name intent
const ACCOUNT_ARGUMENT = 'account';
const BALANCE_ARGUMENT = 'balance';


exports.balanceChecker = functions.https.onRequest((request, response) => {
  const app = new App({request, response});
  console.log('Request headers: ' + JSON.stringify(request.headers));
  console.log('Request body: ' + JSON.stringify(request.body));


// c. The function that generates the silly name
  function giveBalance (app) {
    let account = app.getArgument(ACCOUNT_ARGUMENT);
    let balance = request.get('https://rotterdam.tcbmedia.eu/user.php?type=informationAboutUser&idUser=3')
    app.tell('Alright, your balance on ' +
      account + ' is ' + balance +
      '! I hope you like this number.');
  }
  // d. build an action map, which maps intent names to functions
  let actionMap = new Map();
  actionMap.set(BALANCE_ACTION, giveBalance);


app.handleRequest(actionMap);
});

// /* ASK FOR BALANCE */
// // a. the action name from the make_name Dialogflow intent
// const FIRST_ACTION = 'give_balance';
// // b. the parameters that are parsed from the make_name intent
// const BALANCE_ARGUMENT = 'number';

// exports.getBalance = functions.https.onRequest((req, res) => {
//   const app = new App({ req, res });
//   console.log('Request headers: ' + JSON.stringify(request.headers));
//   console.log('Request body: ' + JSON.stringify(request.body));

//     // c. The function that generates the balance
//     function giveBalance (app) {
//       // let number = app.getArgument(BALANCE_ARGUMENT);
//       let number = 
//       app.tell('Alright, your balance is ' + number + '!');
//     }
//     // d. build an action map, which maps intent names to functions
//     let actionMap = new Map();
//     actionMap.set(TEST_ACTION, giveBalance);

//     app.handleRequest(actionMap);

// })