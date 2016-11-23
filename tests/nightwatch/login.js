module.exports = {
    'Test Login' : function (client) {
        client
            .url('http://dev.portal-mydomain.co.za/login')
            .waitForElementVisible('body', 1000)
            .assert.title('MyDomain Portal')
            .setValue('#email', 'gio@incendiaryblue.com')
            .setValue('#password', 'password')
            .click('button.button')
            .pause(1000)
            .end();
    },

    'Test Unit filter' : function(client) {
        client
            .url('http://dev.portal-mydomain.co.za/units')
            .waitForElementVisible('body', 1000)
            .setValue('input[type="text"][placeholder="Filter by everything"]', 'gio@incendiaryblue.com')
            .click('.shrink button')
            .pause(5000)
            .end();
    }

};