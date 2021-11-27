<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { SVGRenderer } from 'echarts/renderers';
import { HeatmapChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
  VisualMapComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';
var jz = require('jeezy');

use([
  SVGRenderer,
  HeatmapChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
  VisualMapComponent,
]);

export default {
  name: 'CorrelogramBasic',
  components: {
    VChart,
  },
  provide: {
    [THEME_KEY]: 'light',
  },
  props: {
    data: {
      type: Array,
      required: true,
    },
    columns: {
      type: Array,
      required: true,
    },
  },
  computed: {
    option() {
      var corr = jz.arr.correlationMatrix(this.data, this.columns);
      var correlations = corr.map(function(d){
        return d.correlation;
      });

      var min = Math.floor(Math.min(...correlations) || 0);
      var max = Math.ceil(Math.max(...correlations) || 1);

      const matrix = corr.map(function(item) {
        return [item.column_x, item.column_y, item.correlation.toFixed(2)];
      });

      return {
        color: ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8'],
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              title: 'Save',
              show: true,
            },
          },
        },
        tooltip: {
          position: 'top',
        },
        grid: {
          height: '90%',
          top: 5,
        },
        xAxis: {
          type: 'category',
          data: this.columns,
          splitArea: {
            show: true,
          },
          axisLabel: {
            interval: 0,
            formatter: function(param) {
              if (/\s/g.test(param)) {
                return param.split(' ').map(function(item){
                  return item[0];
                }).join('');
              }
              return param;
            },
          },
        },
        yAxis: {
          type: 'category',
          data: this.columns,
          splitArea: {
            show: true,
          },
          axisLabel: {
            interval: 0,
            rotate: 30,
            formatter: function(param) {
              if (/\s/g.test(param)) {
                return param.split(' ').map(function(item){
                  return item[0];
                }).join('');
              }
              return param;
            },
          },
        },
        visualMap: {
          min: min,
          max: max,
          calculable: true,
          orient: 'vertical',
          left: 'right',
          top: '23%',
          inRange: {
            color: ['#74adc7', '#304156'],
          },
        },
        series: [{
          type: 'heatmap',
          data: matrix,
          label: {
            show: true,
          },
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowColor: 'rgba(0, 0, 0, 0.5)',
            },
          },
        }],
      };
    },
  },
};
</script>

<style scoped>
.chart {
  height: 400px;
}
</style>
