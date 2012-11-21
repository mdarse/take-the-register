App.Views.LessonView = Backbone.View.extend({

    initialize: function(options) {
        this.template = Handlebars.templates.lesson;
        this.students = options.studentCollection;

        this.students.on('change reset', this.render, this);
        this.model.on('change', this.render, this);
    },

    render: function() {
        this.$el.html(this.template({ lesson: this.model.toJSON() }));
        var elements = this.students.map(function(student) {
            var view = new App.Views.StudentItemView({ model: student });
            return view.render().el;
        });
        this.$el.append(elements);
        return this;
    }

});