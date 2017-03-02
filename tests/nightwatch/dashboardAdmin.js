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

    'Dashboard check layout' : function(client) {
        client
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .assert.containsText('h2', 'DASHBOARD')
            .assert.containsText('.open-applications .table__row.table__row--add', 'Open Applications')
            .assert.containsText('.pending-applications .table__row.table__row--add', 'Pending Applications')

    },

    'Dashboard check navigate to application review' : function(client) {
        client
            .waitForElementVisible('body', 1000)
            // Fill in nothing and fail
            .click('.row.table>.columns:last-of-type a')
            .waitForElementVisible('body', 1000)
            .assert.containsText('h2', 'APPLICATION REVIEW')

    },

};