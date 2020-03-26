"use strict"
const http = require('http');
const Xray = require('x-ray')

const PORT = "8080";
const dataURL = "https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-cases?";


var scrapeData = new Promise(function(resolve, reject) {
    const req = Xray()
    const resultSet = [];
    req(dataURL + Math.random(), 'table tbody', [{
        results: req('tr', ['tr'])
    }]).then(function(response) {

        const table = response[1]['results'];
        table.forEach(result => {
            const results = result.replace(/\t/g, "").split("\n");
            if (results[1] === "DHB" || results[1] === "Total") return;

            resultSet.push({
                dhb: results[1],
                confirmed: results[2],
                probable: results[3],
                total: results[4],
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