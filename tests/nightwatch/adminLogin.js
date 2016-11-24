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

    'Admin Add Location fail' : function(client) {
        var locationName = 'Nightwatch Location '+ new Date().toISOString();
        client
            .url('http://dev.portal-mydomain.co.za/locations')
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .click('button[name="showAddForm"]')
            .click('button[name="addLocation"]')
            .pause(1000)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the location name and fail
            .setValue('#locationName', locationName)
            .click('button[name="addLocation"]')
            .pause(1000)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the address and fail
            .setValue('#locationAddress', 'Address ' +locationName)
            .click('button[name="addLocation"]')
            .pause(1000)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .click('#locationCity')
            .click('#locationCity>option:last-child')
            .click('button[name="addLocation"]')
            .pause(1000)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill out the region and code (Since code is not mandatory)
            .setValue('#locationRegion', 'Region ' +locationName)
            .setValue('#locationCode', Math.floor((Math.random() * 9999) + 1))
            .click('button[name="addLocation"]')
            .pause(1000)
            // Let's see if the accordion is there.
            .assert.containsText('.row>.columns:last-of-type>.accordion__heading', locationName)
            .pause(1000)
    },

    'Admin Edit location' : function (client) {
        var locationName = 'Nightwatch Location '+ new Date().toISOString();
        client
            .click('.row>.columns:last-of-type>.accordion__heading')
            .pause(300)
            // Let's update the entry to see if it works correctly
            .click('.accordion__content--active button')
            .expect.element('.sweet-alert').to.not.be.visible
            .click('.row>.columns:last-of-type>.accordion__heading')
            .clearValue('#editLocationName')
            .pause(1000)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .pause(5000)
            .end();
    },



};