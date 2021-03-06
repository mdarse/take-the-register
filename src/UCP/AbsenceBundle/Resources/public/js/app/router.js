App.Router = Backbone.Router.extend({
    routes: {
        "": "home",
        "planning": "planning",
        "planning/:id": "lesson",
        "absences": "absences",
        "students": "students",
        "students/:id": "studentDetails",
        "groups": "groups",
        "team": "team"
    },

    initialize: function() {
        this.headerView = new App.Views.HeaderView({
            el: '#header',
            username: App.currentUsername
        }).render();

        this.$content = $('#content');
        
        this.studentCollection = new App.Models.StudentCollection();
        this.lessonCollection = new App.Models.LessonCollection();
    },

    home: function home() {
        this.navigate("planning", {trigger: true});
    },

    // Planning
    planning: function() {
        if (!this.planningView) {
            this.planningView = new App.Views.PlanningView({ collection: this.lessonCollection });
        }
        this.lessonCollection.fetch();
        this.$content.html(this.planningView.el);
        this.headerView.select('planning');
    },

    lesson: function(id) {
        var lesson = new App.Models.Lesson({ id: id });

        var lessonView = this.lessonView = new App.Views.LessonView({
            studentCollection: this.studentCollection,
            lesson: lesson
        }).render();

        // Fetch fresh data from server
        lesson.fetch();
        this.studentCollection.fetch();

        this.$content.html(this.lessonView.el);
        this.headerView.select('planning');
    },

    // Abences
    absences: function() {
        if (!this.absencesView) {
            this.absencesView = new App.Views.AbsencesView({
                students: this.studentCollection
            });
        }
        this.$content.html(this.absencesView.render().el);
        this.headerView.select('absences');

        // Fetch student as it may be empty
        this.studentCollection.fetch();
    },

    // Students
    students: function() {
        if (!this.studentsView) {
            this.makeStudentsView();
        } else {
            this.studentsView.reset();
        }
        this.$content.html(this.studentsView.render().el);
        this.headerView.select('students');
    },

    studentDetails: function(id) {
        if (!this.studentsView) {
            this.makeStudentsView();
        }
        this.studentsView.show(id);
        this.$content.html(this.studentsView.el);
        this.headerView.select('students');
    },

    makeStudentsView: function() {
        this.studentsView = new App.Views.StudentsView({ collection: this.studentCollection });
        this.studentCollection.fetch({ add: true });
    },

    groups: function() {},
    team: function() {}
});
