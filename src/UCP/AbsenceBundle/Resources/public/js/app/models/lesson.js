App.Models.Lesson = Backbone.Model.extend({

    url: function() {
        return Routing.generate('get_lesson', { lesson: this.get('id') });
    },

    isEditable: function() {
        var now = new Date();
        var start = new Date(this.get('start'));
        var end = new Date(this.get('end'));
        if (now > start && now < end) {
            return true;
        }
        return false;
    }

});
