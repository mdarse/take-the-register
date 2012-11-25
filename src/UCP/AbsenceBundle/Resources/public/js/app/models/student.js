App.Models.Student = Backbone.Model.extend({
    
    url: function() {
        return Routing.generate('get_student', { student: this.get('id') });
    }

});
