"use strict"
const http = require('http');
const Xray = require('x-ray')

const PORT = "8080";
const dataURL = "https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-cases";


var scrapeData = new Promise(function(resolve, reject) {
    const req = Xray()
    const resultSet = [];
    req(dataURL, 'table tbody', [{
        results: req('tr', ['tr'])
    }]).then(function(response) {

        const table = response[0]['results'];
        table.forEach(result => {
            result = result.replace(/\t/g, "").split("\n");
            resultSet.push({
                number: result[1],
                location: result[2],
                age: result[3],
                gender: result[4],
                notes: result[5]
            });

        });

        resolve(resultSet)
    })
})

http.createServer(function(request, response) {
    response.writeHead(200, {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*',
    });
    scrapeData.then(function(responseData) {
        response.write(JSON.stringify(responseData));
        response.end();
    })

}).listen(PORT);

console.log("Listening on port " + PORT);