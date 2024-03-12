const Filter = require('bad-words');
const filter = new Filter();

const frenchBadWords = [
    "putain", "merde", "bordel", "putain de merde", "bordel de merde", 
    "putain de bordel de merde", "nom de dieu", "nom de dieu de merde", "ostie", 
    "tabarnak", "crisse", "calisse", "sacrament", "connerie", "con", "connard", 
    "connasse", "saloperie", "salaud", "salopard", "salope", "pute", "putain", 
    "garce", "traînée", "pouffiasse", "pouffe", "chatte", "plotte", "tas de merde", 
    "gros tas", "trou du cul", "lèche-cul", "téteux", "fils de pute", "couilles", 
    "casse-couilles", "casser les couilles", "péter les couilles", "enculer", 
    "enculé", "enculée", "branler", "branleur", "branleuse", "emmerder", 
    "emmerdeur", "emmerdeuse", "chier", "chieur", "chieuse", "chiant", "chiante", 
    "bite", "tête de nœud", "queutard", "metteux", "ferme ta gueule", "va te faire foutre", 
    "va te crosser", "niquer", "nique ta mère", "mère la pute"
];

filter.addWords(...frenchBadWords);

// let sentence = "Hello, this is a test sentence with some bad words and maybe some mauvais mot1.";
// let filteredSentence = filter.clean(sentence);

// console.log(filteredSentence);