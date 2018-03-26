'use strict';

process.env.DEBUG = 'actions-on-google:*';
const App = require('actions-on-google').DialogflowApp;
const functions = require('firebase-functions');
const request = require('request');
const https = require('https');

// a. the action name from the make_name Dialogflow intent
// example intent
const MAKE_ACTION = 'make_name';
// balance checking intent
const BALANCE_ACTION = 'accountBalanceCheck';
// money transfer intent
const TRANSFER_ACTION = 'transferMoney';
// stock market intent
const STOCK_MARKET_ACTION = 'stock_market';
// end the conversation intent
const END_ACTION = 'endConversation';

// b. 1. the parameters that are parsed from the make_name example intent
const COLOR_ARGUMENT = 'color';
const NUMBER_ARGUMENT = 'number';

// b. 2. the parameters that are parsed from the check balance intent
const ACCOUNT_ARGUMENT = 'account';

// b. 3. the parameters that are parsed from the money transfer intent
const FIRSTNAME_ARGUMENT = 'firstName';
const LASTNAME_ARGUMENT = 'lastName';
const AMOUNT_ARGUMENT = 'amount';
const COMMUNICATION_ARGUMENT = 'communication';

// b. 4. the parameters that are parsed from the stock market intent
const MARKET_ARGUMENT = 'market';
const STOCK_ARGUMENT = 'stock';
const TIME_ARGUMENT = 'time'
// c. export a module containing the differents functions
exports.leoBank = functions.https.onRequest((request, response) => {
  // 1. declaring our app so that we can make requests
  const app = new App({
    request,
    response
  });
  // 2. Logging the request
  console.log('Request headers: ' + JSON.stringify(request.headers));
  console.log('Request body: ' + JSON.stringify(request.body));

  // 3.1 The function that generates the silly name for the make_name intent
  function makeName(app) {
    let number = app.getArgument(NUMBER_ARGUMENT);
    let color = app.getArgument(COLOR_ARGUMENT);
    app.tell('Alright, your silly name is ' +
      color + ' ' + number +
      '! I hope you like it. See you next time.');
  }

  // 3.2 The function that gives the balance on the asked account
  function giveBalance(app) {
    // Keeping track of the version of the function that will be deployed
    console.log('giveBalance v512');
    // Get the account information based on the prompt filled in by the user
    let account = app.getArgument(ACCOUNT_ARGUMENT);

    // make the request to our api to have the informations
    https.get('https://rotterdam.tcbmedia.eu/user/3/accounts/', (res) => {
      // declaring the body
      let body = '';
      // checking the status of the request
      console.log('statusCode:', res.statusCode);
      // On response, fill the data inside the body
      res.on('data', (data) => {
        body += data;
      });
      // Once the body is filled with the informations
      res.on('end', () => {
        // parse the body
        body = JSON.parse(body);
        // declaring the variables for the parameters of our JSON
        let typeString = '';
        let accountBalance = '';
        // Checking if the user wants to see the balance on the checking or savings account
        body.accounts.forEach(item => {
          typeString = item.typeAccount;
          typeString = typeString.toLowerCase();
          if (typeString + ' account' === account) {
            accountBalance = item.balance;
          }
        });
        // Answering the user with the sentence below
        app.ask('Alright, your balance on ' + account + ' is ' + accountBalance + '!');
      });
      // Handling erros
    }).on('error', (e) => {
      console.error(e);
    });

  }

  // 3. 3. The function that generates the transaction
  function transferMoney(app) {
    // Keeping track of the version of the function that will be deployed      
    console.log('transfetMoney v605');
    // declaring the IBAN account that will be used
    let fromIban = 'NL3333333333333333';
    // Get the firstname & lastname based on the prompt filled in by the user 
    let firstName = app.getArgument(FIRSTNAME_ARGUMENT);
    let lastName = app.getArgument(LASTNAME_ARGUMENT);
    // Concataining the received names
    let name = lastName + ' ' + firstName;
    // Get the amount based on the prompt filled in by the user       
    let amount = app.getArgument(AMOUNT_ARGUMENT);
    // Get the structured communication based on the prompt filled in by the user       
    let communication = encodeURI(app.getArgument(COMMUNICATION_ARGUMENT));
    // Injecting the parameters in the url of the API
    let transferUrl = 'https://rotterdam.tcbmedia.eu/operation.php?fromIban=' + fromIban + '&toName=' + encodeURI(name) + '&amount=' + amount + '&communication=' + communication + '&type=bankTransfertWithName';
    // make the request to our transfer URL
    https.get(transferUrl, (res) => {
      // declaring the body
      let body = '';
      // checking the status of the request
      console.log('statusCode:', res.statusCode);
      // On response, fill the data inside the body        
      res.on('data', (data) => {
        body += data;
      });
      // Once the body is filled with the informations        
      res.on('end', () => {
        // parse the body          
        body = JSON.parse(body);
        // Logging the body's response
        console.log('on res transfer body', body);
        // Answering the user with the sentence below          
        app.ask('Alright, your transfer of ' + amount + '€ to ' + name + ' has been successfully done !');
      });
      // Handling erros        
    }).on('error', (e) => {
      console.error(e);
    });

  }

  // 3.4 Accessing exteranl APIs such as getting the current value of the stock market
  function getMarketData(app) {
    // Keeping track of the version of the function that will be deployed      
    console.log('getMarketData v36');
    // Declaring the market based on the prompt filled in by the user
    let market = app.getArgument(MARKET_ARGUMENT);
    let stock = app.getArgument(STOCK_ARGUMENT);
    let time = app.getArgument(TIME_ARGUMENT);

    // Define the API's route based on the stock parameter
    let stockMarketUrl = '';
    function getStockMarketUrl(){
      // Facebook
    if (stock === 'Facebook' || stock === 'FB' || stock === 'facebook') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/FB/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("facebook url", stockMarketUrl);
    }
    // Google    
    else if (stock === "Google" || stock === 'GOOGL' || stock === 'Alphabet' || stock === 'Alphabet inc' || stock === 'Alphabet Inc') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/GOOGL/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Google url", stockMarketUrl);
    }
    // Amazon    
    else if (stock === "Amazon" || stock === 'AMZN' || stock === 'amazon' || stock === 'Amazon.com' || stock === 'Amazon.com Inc' || stock === 'Amazon.com inc' || stock === 'Amazon inc' || stock === 'Amazon Inc') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/AMZN/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Amazon url", stockMarketUrl);
    }
    // Apple    
    else if (stock === 'Apple' || stock === 'AAPL' || stock === 'Apple Inc.' || stock === 'Apple Inc' || stock === 'Apple inc' || stock === 'apple') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/AAPL/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Apple url", stockMarketUrl);
    }
    // Microsoft    
    else if (stock === "Microsoft" || stock === 'MSFT' || stock === 'Microsoft Corporation' || stock === 'Microsoft Corp' || stock === 'Microsoft Corp.') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/MSFT/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Microsoft url", stockMarketUrl);
    }
    // Intel
    else if (stock === "Intel" || stock === 'INTC' || stock === 'Intel Corporation' || stock === 'Intel Corpor' || stock === 'Intel Corp.') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/INTC/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Intel url", stockMarketUrl);
    }
    // Cisco
    else if (stock === "Cisco" || stock === 'CSCO' || stock === 'CISCO' || stock === 'Cisco Systems, Inc.' || stock === 'Cisco Systems' || stock === 'Cisco Systems, Inc' || stock === 'Cisco Inc.' || stock === 'Cisco Inc' || stock === 'CISCO Inc.' || stock === 'Cisco inc' || stock === 'CISCO Inc' || stock === 'CISCO inc') {
      stockMarketUrl = 'https://www.quandl.com/api/v3/datasets/WIKI/CSCO/data.json?api_key=Y1G_XZ3Mn18xxsnR1aAf';
      console.log("Cisco url", stockMarketUrl);
    } else {
      app.ask('Sorry I don\'t know that company');
    }
    }

    function makeRequest() {
      // make the request to our stock market URL
      https.get(stockMarketUrl, (res) => {
        // declaring the body
        let body = '';
        // checking the status of the request
        console.log('statusCode:', res.statusCode);
        // On response, fill the data inside the body        
        res.on('data', (data) => {
          body += data;
        });
        // Once the body is filled with the informations        
        res.on('end', () => {
          // parse the body          
          body = JSON.parse(body);
          // Logging the body's response
          console.log('res of stock market body', body);
          // Get the data based on the time parameter
          let data = [];
          if (time === 'Open' || time === 'open') {
            data = body.dataset_data.data[0][1];
            console.log('open', data);
          }

          if (time === 'High' || time === 'high') {
            data = body.dataset_data.data[0][2];
            console.log('high', data);
          }

          if (time === 'Low' || time === 'low') {
            data = body.dataset_data.data[0][3];
            console.log('low', data);
          }
          if (time === 'Close' || time === 'close') {
            data = body.dataset_data.data[0][4];
            console.log('close', data);
          }
          if (time === 'Volume' || time === 'volume') {
            data = body.dataset_data.data[0][5];
            console.log('volume', data);
          }

          // Answering the user with the sentence below          
          app.ask('The stocks of ' + stock + ' are worth ' + data + '$ based on the ' + time + '!');
        });
        // Handling erros        
      }).on('error', (e) => {
        console.error(e);
      });
    }
    // Make the requests for the NASDAQ Market
    if (market === 'NASDAQ' || market === 'nasdaq' || market === 'Nasdaq') {
      getStockMarketUrl();
      makeRequest();
    } else {
      app.ask('Sorry, ' + stock + ' isn\'t on the market you asked');
    }

  }

  // 3.X Declaring the endConversation function just to link the DialogFlow agent to the Google Assistant
  function endConversation(app) {}

  // 4. build an action map, which maps intent names to functions
  let actionMap = new Map();
  actionMap.set(MAKE_ACTION, makeName);
  actionMap.set(BALANCE_ACTION, giveBalance);
  actionMap.set(TRANSFER_ACTION, transferMoney);
  actionMap.set(STOCK_MARKET_ACTION, getMarketData);
  actionMap.set(END_ACTION, endConversation);

  app.handleRequest(actionMap);

});