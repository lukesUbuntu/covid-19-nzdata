"use strict"

const Xray = require('x-ray')
const req = Xray()
const dataURL = "https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-cases";
const NZCOVID = [];
req(dataURL, 'table tbody', [{
    results: req('tr', ['tr'])
}]).then(function(response) {

    const table = response[0]['results'];
    table.forEach(result => {
        NZCOVID.push(result.replace(/\t/g, "").split("\n"))
    });

    console.log("element", NZCOVID)
})