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
        this.$content = $('#content');
        // TODO Make a headerView to handle navigation
    },

    home: function home() {
        this.navigate("students", {trigger: true});
    },

    // Planning
    planning: function() {
        if (!this.planningView) {
            var lessonCollection = new App.Models.LessonCollection();
            this.planningView = new App.Views.PlanningView({ collection: lessonCollection });
            var self = this;
            lessonCollection.fetch({
                success: function() {
                    self.planningView.render();
                }
            });
        }
        this.$content.html(this.planningView.el);
    },

    // Students
    students: function() {
        if (!this.studentsView) {
            this.makeStudentsView();
        } else {
            // this.studentsView.reset();
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
        this.studentsView = new App.Views.StudentsView({ collection: studentCollection });
        var self = this;
        studentCollection.fetch({
            success: function() {
                self.studentsView.render();
            }
        });
    },

    groups: function() {},
    team: function() {}
});
