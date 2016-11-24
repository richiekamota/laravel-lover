module.exports = {
    'Admin Login' : function (client) {
        client
            .url('http://dev.portal-mydomain.co.za/login')
            .waitForElementVisible('body', 1000)
            .assert.title('MyDomain Portal')
            .setValue('#email', 'gio@incendiaryblue.com')
            .setValue('#password', 'password')
            .click('button.button')
            .pause(1000)
    },

    'Admin Add Location' : function(client) {
        var locationName = 'Nightwatch Location '+ new Date().toISOString();
        client
            .url('http://dev.portal-mydomain.co.za/locations')
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .click('button[name="showAddForm"]')
            .click('button[name="addLocation"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the location name and fail
            .setValue('#locationName', locationName)
            .click('button[name="addLocation"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the address and fail
            .setValue('#locationAddress', 'Address ' +locationName)
            .click('button[name="addLocation"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .click('#locationCity')
            .click('#locationCity>option:last-child')
            .click('button[name="addLocation"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill out the region and code (Since code is not mandatory)
            .setValue('#locationRegion', 'Region ' +locationName)
            .setValue('#locationCode', Math.floor((Math.random() * 9999) + 1))
            .click('button[name="addLocation"]')
            .pause(1500)
            // Let's see if the accordion is there.
            .assert.containsText('.row>.columns:last-of-type>.accordion__heading', locationName)
            .pause(1500)
    },

    'Admin Edit location' : function (client) {
        var locationName = 'Nightwatch Location '+ new Date().toISOString();
        client
            .click('.row>.columns:last-of-type>.accordion__heading')
            .pause(600)
            // Let's update the entry to see if it works correctly
            .click('.accordion__content--active button')
            .verify.hidden('.sweet-alert')
            .pause(850)
            // Delete the location name and try to submit
            .click('.row>.columns:last-of-type>.accordion__heading')
            .pause(600)
            .clearValue('.accordion__content--active #editLocationName')
            // We do this to update vue
            .setValue('.accordion__content--active #editLocationName', [' ',client.Keys.BACK_SPACE])
            .click('.accordion__content--active>.button')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in a location name, remove the address and submit
            .clearValue('.accordion__content--active #editLocationAddress')
            .setValue('.accordion__content--active #editLocationAddress', [' ',client.Keys.BACK_SPACE])
            .setValue('.accordion__content--active #editLocationName', locationName)
            .click('.accordion__content--active .button')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .clearValue('.accordion__content--active #editLocationRegion')
            .setValue('.accordion__content--active #editLocationRegion', [' ',client.Keys.BACK_SPACE])
            .setValue('.accordion__content--active #editLocationAddress', 'Address ' +locationName)
            .click('.accordion__content--active>.button')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .end();
    },



};