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
        this.makeStudentsView();
        // this.studentsView.reset();
        this.$content.html(this.studentsView.el);
        // TODO this.headerView.select('students');
    },

    studentDetails: function(id) {
        this.makeStudentsView();
        this.studentsView.show(id);
        this.$content.html(this.studentsView.el);
    },

    makeStudentsView: function() {
        if (!this.studentsView) {
            this.studentsView = new App.Views.StudentsView();
            this.studentsView.render();
        }
    },

    planning: function() {},
    groups: function() {},
    team: function() {}
});
