<template>
<main class="main">
   <div class="row">
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-blackberry">
            <div class="card-body" style="height: 50px !important;">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">45%</h3>
                            <span>Ocupación del día</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="icon-pie-chart font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-ibiza-sunset">
            <div class="card-body" style="height: 50px !important;">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">55%</h3>
                            <span>Tarifa promedio</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="icon-bulb font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart1" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-green-tea">
            <div class="card-body" style="height: 50px !important;">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">60%</h3>
                            <span>Llegadas</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="icon-graph font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart2" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card gradient-pomegranate">
            <div class="card-body" style="height: 50px !important;">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h3 class="font-large-1 mb-0">30%</h3>
                            <span>Salidas</span>
                        </div>
                        <div class="media-right white text-right">
                            <i class="icon-wallet font-large-1"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart3" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
 <div class="col-xl-3 col-lg-6 col-md-6 col-12">
     <div class="card gradient-blackberry">
         <div class="card-body" style="height: 50px !important;">
             <div class="card-block pt-2 pb-0">
                 <div class="media">
                     <div class="media-body white text-left">
                         <h3 class="font-large-1 mb-0">87%</h3>
                         <span>Ventas del día</span>
                     </div>
                     <div class="media-right white text-right">
                         <i class="icon-pie-chart font-large-1"></i>
                     </div>
                 </div>
             </div>
             <div id="Widget-line-chart" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
             </div>
         </div>
     </div>
 </div>
 <div class="col-xl-3 col-lg-6 col-md-6 col-12">
     <div class="card gradient-ibiza-sunset">
         <div class="card-body" style="height: 50px !important;">
             <div class="card-block pt-2 pb-0">
                 <div class="media">
                     <div class="media-body white text-left">
                         <h3 class="font-large-1 mb-0">25%</h3>
                         <span>Cancelaciones del dia</span>
                     </div>
                     <div class="media-right white text-right">
                         <i class="icon-bulb font-large-1"></i>
                     </div>
                 </div>
             </div>
             <div id="Widget-line-chart1" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
             </div>

         </div>
     </div>
 </div>

 <div class="col-xl-3 col-lg-6 col-md-6 col-12">
     <div class="card gradient-green-tea">
         <div class="card-body" style="height: 50px !important;">
             <div class="card-block pt-2 pb-0">
                 <div class="media">
                     <div class="media-body white text-left">
                         <h3 class="font-large-1 mb-0">40%</h3>
                         <span>Llegadas pendientes</span>
                     </div>
                     <div class="media-right white text-right">
                         <i class="icon-graph font-large-1"></i>
                     </div>
                 </div>
             </div>
             <div id="Widget-line-chart2" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
             </div>
         </div>
     </div>
 </div>
 <div class="col-xl-3 col-lg-6 col-md-6 col-12">
     <div class="card gradient-pomegranate">
         <div class="card-body" style="height: 50px !important;">
             <div class="card-block pt-2 pb-0">
                 <div class="media">
                     <div class="media-body white text-left">
                         <h3 class="font-large-1 mb-0">80%</h3>
                         <span>Salidas pendientes</span>
                     </div>
                     <div class="media-right white text-right">
                         <i class="icon-wallet font-large-1"></i>
                     </div>
                 </div>
             </div>
             <div id="Widget-line-chart3" class="height-75 WidgetlineChart WidgetlineChartshadow mb-2">
             </div>
         </div>
     </div>
 </div>
</div>
<!--Statistics cards Ends-->

<!--Line with Area Chart 1 Starts-->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">ESTADISTICAS</h4>
            </div>
            <div class="card-body">

<apexchart height="200" type="line" :options="chartOptions" :series="series"/>

            </div>
        </div>
    </div>
</div>
<!--Line with Area Chart 1 Ends-->


                </div>
            </div>
        </div>
    </div>
</div>


</main>
</template>
<script>

  import VueApexCharts from "vue-apexcharts";


    export default {
        components: {apexchart: VueApexCharts },
        data (){
            return {
                base:'',
                chartOptions: {
         xaxis: {
           type: "datetime",
           categories: [
             "2019-01-01",
             "2019-02-01",
             "2019-03-01",
             "2019-04-01",
             "2019-05-01",
             "2019-06-01",
             "2019-07-01"
           ]
         }
       },
       series: [
         {
           name: "WebsiteHits",
           data: [15, 20, 21, 25, 60, 120, 93]
         }
       ]

            }
        },
        methods : {
        },

        mounted() {
            this.base=base;
        }

    }
</script>
<style>
  .card .card-body {
      padding: 0 !important;
      height: 70px !important;
  }

</style>
