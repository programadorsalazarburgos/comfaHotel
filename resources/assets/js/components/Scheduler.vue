<script>
	
        props: ['id', 'config'],
        template: '<div :id="id"></div>',
        mounted: function () {
            this.control = new DayPilot.Scheduler(this.id, this.config).init();
        }
    
</script>	