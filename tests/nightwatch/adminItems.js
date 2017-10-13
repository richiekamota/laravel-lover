module.exports = {
    'Admin Login' : function (client) {
        client
            .url('http://localhost:8000/login')
            .waitForElementVisible('body', 1000)
            .assert.title('MyDomain Portal')
            .setValue('#email', 'first_last@test.com')
            .setValue('#password', 'qwerty')
            .click('button.button')
            .pause(1000)
            .url('http://localhost:8000/items')
    },

    'Admin Add Item' : function(client) {
        var itemName = 'Nightwatch Item '+ new Date().toISOString();
        client
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .click('.accordion__heading--add')
            .pause(500)
            .click('button[name="addItem"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the unit type name and fail
            .setValue('#itemName', itemName)
            .click('button[name="addItem"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill in the description and wait for an error
            .setValue('#itemDescription', 'Nightwatch item description')
            .click('button[name="addItem"]')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            // Fill out the cost and submit.
            .setValue('#itemCost', Math.floor((Math.random() * 9999) + 1))
            .click('[name="itemUnitTypes"] option:first-of-type')
            .pause(500)
            .click('button[name="addItem"]')
            .pause(1500)
            // Let's see if the accordion is there.
            .assert.containsText('.row.table>.columns:last-of-type .accordion__heading', itemName)
            .pause(1500)
    },

    'Admin Edit Item' : function (client) {
        client
            .click('.row.table>.columns:last-of-type .accordion__heading')
            .pause(600)
            // Let's update the entry to see if it works correctly
            .click('.accordion__content--active .button.success')
            .verify.hidden('.sweet-alert')
            .pause(850)
            // Delete the price and try to submit
            .click('.row.table>.columns:last-of-type .accordion__heading')
            .pause(600)
            .clearValue('.accordion__content--active #editItemCost')
            // We do this to update vue
            .setValue('.accordion__content--active #editItemCost', [' ',client.Keys.BACK_SPACE])
            .click('.accordion__content--active>.button')
            .pause(1500)
            .assert.containsText('.showSweetAlert h2', 'Error!')
            .click('.sa-confirm-button-container .confirm')
            .end();
    },

};