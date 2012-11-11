App.Router = Backbone.Router.extend({
    routes: {
        "": "home",
        "planning": "planning",
        "students": "students",
        "students/:id": "studentDetails",
        "groups": "groups",
        "team": "team"
    },

    initialize: function() {
        console.log("Router:initialize");
        this.$content = $('#content');
        // TODO Make a headerView to handle navigation
    },

    home: function home() {
        this.navigate("students", {trigger: true});
    },

    students: function() {
        if (!this.studentsView) {
            this.makeStudentsView();
        }
        this.$content.html(this.studentsView.el);
        // TODO this.headerView.select('students');
    },

    studentDetails: function(id) {
        if (!this.studentsView) {
            this.makeStudentsView();
        }
        this.studentsView.show(id);
        this.$content.html(this.studentsView.el);
    },

    makeStudentsView: function() {
        var studentCollection = new App.Models.StudentCollection();
        this.studentsView = new App.Views.StudentsView({ collection: studentCollection});
        var router = this;
        studentCollection.fetch({
            success: function() {
                router.studentsView.render();
            }
        });
    },

    planning: function() {},
    groups: function() {},
    team: function() {}
});
