App.Views.PlanningView = Backbone.View.extend({

    className: 'planning',

    initialize: function() {
        this.lessons = this.collection;
        this.lessons.on('add change remove reset', this.render, this);
    },

    render: function() {
        //transformation de l'objet lessons en tableau
        var lessons = this.lessons.toArray();
        var lessonsDay = new Array();
        var lessonsDate;
        //Récuperation de la date pour trier les lessons au format YYY-MM-DD
        var currentLessonDate = lessons[0].get('start');
        currentLessonDate = currentLessonDate.split("T");
        currentLessonDate = currentLessonDate[0];
        //parcour du tableau 'lessons'
        for (var i = 0, l = lessons.length; i < l; i++) {
           //Recuperation de la date de la lesson en cours
           lessonsDate = lessons[i].get('start');
           lessonsDate = lessonsDate.split("T");

           if(lessonsDate[0] == currentLessonDate){
               lessonsDay.push(lessons[i]);
           }
           else{
                //création de la vue
                var view = new App.Views.PlanningDayView({
                    date: currentLessonDate,
                    lessons: lessonsDay
                });
                view.render();
                this.$el.append(view.el);
                //réinitialisation du tableau
                lessonsDay=[];
                //Date suivante
                currentLessonDate = lessons[i].get('start');
                currentLessonDate = currentLessonDate.split("T");
                currentLessonDate = currentLessonDate[0];
                lessonsDay.push(lessons[i]);
           }
           
        };
        //Création de la derniere vue avec les derniere lessons testé.
        var view = new App.Views.PlanningDayView({
                    date: currentLessonDate,
                    lessons: lessonsDay
                });
                view.render();
                this.$el.append(view.el);
        return this;
    }

});