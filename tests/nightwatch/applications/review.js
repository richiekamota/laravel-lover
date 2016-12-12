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
            .url('http://localhost:8000/dashboard')
    },

    'Navigate to application' : function(client) {
        client
            .waitForElementVisible('body', 1000)
            .click('.row.table>.columns:last-of-type a')
            .waitForElementVisible('body', 1000)
    },

    'View application setup' : function(client) {
        client
            // Fill in nothing and fail
            .assert.containsText('#heading-user-details', 'User Details')
            .assert.containsText('#heading-leaseholder', 'Leaseholder details')
            .assert.containsText('#heading-leaseholder-home-ownership', 'Leaseholder home ownership')
            .assert.containsText('#heading-employment-status', 'Employment status')
            .assert.containsText('#heading-resident-details', 'Resident details')
            .assert.containsText('#heading-premises', 'Details of the premises')
            .assert.containsText('#heading-general', 'General')
            .assert.containsText('#heading-supporting', 'Supporting documents')
            .assert.containsText('#heading-comments', 'Comments')
            .assert.containsText('#approve-application', 'APPROVE')
            .assert.containsText('#pending-application', 'PENDING')
            .assert.containsText('#decline-application', 'DECLINE')
            .assert.elementNotPresent('#confirm-decline', 'CONFIRM DELETE')
            .assert.elementNotPresent('#cancel-decline', 'CANCEL')

    },

    'View decline application setup' : function(client) {
        client
            // Click on the decline button and see there are two more appearing
            .click('#decline-application')
            .assert.visible("#confirm-decline");


    },

};