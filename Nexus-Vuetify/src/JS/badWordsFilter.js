// badWordsFilter.js
const Filter = require('bad-words');
const filter = new Filter();

let sentence = "Hello, this is a test sentence with some bad words.";
let filteredSentence = filter.clean(sentence);

console.log(filteredSentence);








