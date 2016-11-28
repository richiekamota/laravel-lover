module.exports = {
    'Tenant Register' : function (client) {
        client
            .url('http://localhost:8000/application-form')
            .waitForElementVisible('body', 1000)
            .setValue('#first_name', 'NightWatcher')
            .setValue('#last_name', 'Nightwatched')
            .setValue('#email', 'nightwatch' + Math.floor((Math.random() * 9999) + 1) + '@nightwatch.com')
            .setValue('#password', 'nightwatch')
            .click('button.success')
            .pause(1000)
    },

    'Tenant application form step 1' : function(client) {
        client
            .waitForElementVisible('body', 1000)
            .pause(1000)
            .moveToElement('#step1-save', 0,0)
            .pause(1000)
            // Fill in nothing and fail
            .click('#step1-save')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the ID Number and fail
            .setValue('input[name="sa_id_number"]', '123465' + Math.floor((Math.random() * 999999) + 1))
            .click('#step1-save')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in a DOB
            .click('.flatpickr-input')
            .click('.flatpickr-day')
            .click('#step1-save')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Chose nationality
            .click('select[name="nationality"]')
            .click('select[name="nationality"]>option:first-child')
            .click('#step1-save')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the address
            .setValue('input[name="current_address"]', 'Address yay')
            .click('#step1-save')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            //Chose a marital status, chose the one which requires the if married to be chosen
            .click('select[name="marital_status"]')
            .click('select[name="marital_status"]>option[value="Married"]')
            .click('#step1-save')
            // Chose the married type
            .click('select[name="married_type"]')
            .click('select[name="married_type"]>option:first-child')
            .click('#step1-save')
            // Confirm and continue
            .end();
    },


};