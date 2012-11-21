App.Router = Backbone.Router.extend({
    routes: {
        "": "home",
        "planning": "planning",
        "planning/:id": "lesson",
        "students": "students",
        "students/:id": "studentDetails",
        "groups": "groups",
        "team": "team"
    },

    initialize: function() {
        this.$content = $('#content');
        // TODO Make a headerView to handle navigation
        
        this.studentCollection = new App.Models.StudentCollection();
    },

    home: function home() {
        this.navigate("students", {trigger: true});
    },

    // Planning
    planning: function() {
        if (!this.planningView) {
            var lessonCollection = new App.Models.LessonCollection();
            this.planningView = new App.Views.PlanningView({ collection: lessonCollection });
            lessonCollection.fetch();
        }
        this.$content.html(this.planningView.el);
    },

    lesson: function(id) {
        if (!this.lessonView) {
            var lesson = new App.Models.Lesson({ id: id });
            this.lessonView = new App.Views.LessonView({
                studentCollection: this.studentCollection,
                model: lesson
            });
            lesson.fetch();
            this.studentCollection.fetch();
        }
        this.$content.html(this.lessonView.el);
    },

    // Students
    students: function() {
        if (!this.studentsView) {
            this.makeStudentsView();
        } else {
            this.studentsView.reset();
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
        this.studentsView = new App.Views.StudentsView({ collection: this.studentCollection });
        this.studentCollection.fetch();
    },

    groups: function() {},
    team: function() {}
});
