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
            .url('http://dev.portal-mydomain.co.za/unit-types')
    },

    'Admin Add Unit Type' : function(client) {
        var unitTypeName = 'Nightwatch Unit '+ new Date().toISOString();
        client
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .click('button[name="showAddForm"]')
            .click('button[name="addUnitType"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the unit type name and fail
            .setValue('#unitTypeName', unitTypeName)
            .click('button[name="addUnitType"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // TODO
            .click('#locationId')
            .click('#locationId>option:last-child')
            .click('button[name="addUnitType"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill out the region and code (Since code is not mandatory)
            .setValue('#unitTypeCost', Math.floor((Math.random() * 9999) + 1))
            .click('button[name="addUnitType"]')
            .pause(1500)
            // Let's see if the accordion is there.
            .assert.containsText('.row>.columns:last-of-type>.accordion__heading', unitTypeName)
            .pause(1500)
    },

    'Admin Edit Unit Type' : function (client) {
        client
            .click('.row>.columns:last-of-type>.accordion__heading')
            .pause(600)
            // Let's update the entry to see if it works correctly
            .click('.accordion__content--active button')
            .verify.hidden('.sweet-alert')
            .pause(850)
            // Delete the price and try to submit
            .click('.row>.columns:last-of-type>.accordion__heading')
            .pause(600)
            .clearValue('.accordion__content--active #editUnitTypeCost')
            // We do this to update vue
            .setValue('.accordion__content--active #editUnitTypeCost', [' ',client.Keys.BACK_SPACE])
            .click('.accordion__content--active>.button')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .end();
    },



};