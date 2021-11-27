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
  name: 'HeatmapChart',
  components: {
    VChart,
  },
  provide: {
    [THEME_KEY]: 'light',
  },
  props: {
    resultSet: {
      type: Object,
      required: true,
    },
    xtitle: {
      type: String,
      required: false,
      default: null,
    },
    ytitle: {
      type: String,
      required: false,
      default: null,
    },
    curve: {
      type: Boolean,
      required: false,
      default: false,
    },
    legend: {
      type: Boolean,
      required: false,
      default: true,
    },
    area: {
      type: Boolean,
      required: false,
      default: false,
    },
    pivotConfig: {
      type: Object,
      required: true,
      default() {
        return {};
      },
    },
  },
  data: () => ({
    option: null,
  }),
  mounted() {
    this.calculateOption();
  },
  methods: {
    calculateOption() {
      const data = this.resultSet.tablePivot(this.pivotConfig);
      const headers = Object.keys(data[0]).map((key) => {
        return {
          text: key,
          value: key,
        };
      });

      let unique = [...new Set(data.map(item => item[headers[1].text]))];
      unique = unique.filter(item => {
        if (item){
          return item;
        }
      });
      unique = unique.map(item => item ? item.trim() : item);

      var reduced = unique.reduce(function(map, obj) {
        map[obj] = null;
        return map;
      }, {});

      const niz = {};
      const finalData = [];

      data.forEach((item, key) => {
        if (item[headers[1].text] === null) {
          return;
        }
        if (!(item[headers[0].text] in niz)) {
          niz[item[headers[0].text]] = { ...reduced };
        }
        niz[item[headers[0].text]][item[headers[1].text].trim()] = item[headers[headers.length - 1].text];
      });

      for (const property in niz) {
        finalData.push(niz[property]);
      }

      var corr = jz.arr.correlationMatrix(finalData, unique);
      // var correlations = corr.map(function(d){
      //   return d.correlation;
      // });

      var min = -1;
      var max = 1;

      const matrix = corr.map(function(item) {
        return [item.column_x, item.column_y, item.correlation.toFixed(2)];
      });

      this.option = {
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
          height: '100%',
          containLabel: true,
          top: -5,
        },
        xAxis: {
          z: 3,
          position: 'top',
          type: 'category',
          data: [...unique],
          splitLine: {
            show: true,
            interval: 0,
            lineStyle: {
              color: '#fff',
            },
          },
          axisLabel: {
            interval: 0,
            rotate: -90,
            formatter: function(param) {
              if (String(param).length > 10) {
                return param.substring(0, 10) + '.';
              }
              return param;
            },
          },
          axisTick: {
            show: false,
          },
          axisLine: {
            show: false,
          },
        },
        yAxis: {
          z: 3,
          type: 'category',
          data: unique.reverse(),
          splitLine: {
            show: true,
            interval: 0,
            lineStyle: {
              color: '#fff',
            },
          },
          axisLabel: {
            interval: 0,
            formatter: function(param) {
              if (String(param).length > 10) {
                return param.substring(0, 10) + '.';
              }
              return param;
            },
          },
          axisTick: {
            show: false,
          },
          axisLine: {
            show: false,
          },
        },
        visualMap: {
          min: min,
          max: max,
          calculable: true,
          orient: 'vertical',
          left: 'right',
          top: '38%',
          inRange: {
            color: ['#6D0A1E', '#AD1629', '#FFFFFF', '#327CA9', '#265e80'],
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
  height: 600px;
}
</style>
