App.Views.StudentTileView = Backbone.View.extend({

    tagName: 'article',
    className: 'student tile',

    initialize: function(options) {
        this.template = Handlebars.templates['student-tile'];
        this.student = options.student;
        this.student.on('change', this.render, this);
        this.student.on('destroy', this.remove, this);

        // Doesn't change at runtime
        if (this.options.selectable) {
            this.$el.addClass(this.options.selectableClass);
        } else {
            this.$el.removeClass(this.options.selectableClass);
        }
    },

    events: {
        'click': 'click'
    },

    options: {
        selected: false,
        selectedClass: 'selected',
        selectable: false,
        selectableClass: 'selectable'
    },

    render: function() {
        var html = this.template( this.student.toJSON() );
        this.$el.html(html);
        if (this.options.selected) {
            this.$el.addClass(this.options.selectedClass);
        } else {
            this.$el.removeClass(this.options.selectedClass);
        }
        return this;
    },

    click: function() {
        if (!this.options.selectable) return;
        if (this.options.selected) {
            this.unselect();
        } else {
            this.select();
        }
    },

    select: function() {
        this.options.selected = true;
        this.trigger('select', this.student);
        this.render();
    },

    unselect: function() {
        this.options.selected = false;
        this.trigger('unselect', this.student);
        this.render();
    }

});