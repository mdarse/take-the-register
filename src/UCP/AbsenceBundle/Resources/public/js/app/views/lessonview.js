App.Views.LessonView = Backbone.View.extend({

    initialize: function(options) {
        this.template = Handlebars.templates.lesson;
        this.students = options.studentCollection;
        this.lesson   = this.options.lesson;
        this.absences = new App.Models.AbsenceCollection();
        this.fetchAbsences();

        this.students.on('change reset', this.render, this);
        this.lesson.on('change', this.render, this);
        this.absences.on('reset', this.render, this);

        this.subViews = [];
    },

    options: {
        editing: false,
        tileViewMode: true
    },

    events: {
        'click a.view-toggle': 'toggleViewMode',
        'click button.edit': 'edit',
        'click button.save': 'save'
    },

    render: function() {
        this.editable = this.lesson.isEditable();

        var html = this.template({
            lesson: this.lesson.toJSON(),
            editable: this.editable,
            editing: this.options.editing
        });
        this.$el.html(html);

        // Unbind old views to prevent memory leaks
        _.each(this.subViews, function(view) {
            view.off('select', this.selected, this);
            view.off('unselect', this.unselected, this);
        }, this);

        this.subViews = this.students.map(this.makeStudentView, this);

        // Insert in DOM
        _.each(this.subViews, function(view) {
            this.$el.append(view.render().el);
        }, this);

        return this;
    },

    makeStudentView: function(student) {
        var isAbsent = this.absences.any(function(absence) {
            return absence.get('student') === student.id;
        });

        // Make view
        var StudentView = this.options.tileViewMode ?
            App.Views.StudentTileView : App.Views.StudentListView;
        var view = new StudentView({
            student: student,
            lesson: this.lesson,
            selected: isAbsent,
            selectable: this.options.editing
        });

        // Bind events
        view.on('select', this.selected, this);
        view.on('unselect', this.unselected, this);

        return view;
    },

    toggleViewMode: function(e) {
        e.preventDefault();
        this.options.tileViewMode = !this.options.tileViewMode;
        this.render();
    },

    edit: function() {
        this.options.editing = true;
        this.render();
    },

    save: function() {
        this.options.editing = false;
        this.render();
    },

    selected: function(student) {
        // Student was mark absent (create an Absence object)
        this.absences.create({
            student: student.id,
            lesson: this.lesson.id,
            justified: false
        });
    },

    unselected: function(student) {
        // Destroy absence object
        var absence = this.absences.find(function(absence) {
            return absence.get('student') === student.id;
        });
        absence.destroy();
    },

    fetchAbsences: function() {
        this.absences.fetch({
            data: { lesson: this.lesson.id }
        });
    }

});