<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { SVGRenderer } from 'echarts/renderers';
import { LineChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  SVGRenderer,
  LineChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
]);

export default {
  name: 'StackedChart',
  components: {
    VChart,
  },
  provide: {
    [THEME_KEY]: 'light',
  },
  props: {
    resultSet: {
      type: Array,
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
    legend: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  computed: {
    option() {
      if (!this.resultSet.length) {
        return {};
      }
      if (!this.resultSet[0].data[0]) {
        return {};
      }
      const date = new Date(this.resultSet[0].data[0][0]);
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
          trigger: 'axis',
          enterable: true,
          appendToBody: 'true',
          className: 'echarts-tooltip-custom-class',
          formatter: function(params) {
            const header = '<b>' + params[0].axisValueLabel + '</b><br/>';
            let content = '';

            for (let i = 0; i < params.length; i++) {
              if (params[i].value[1] && params[i].value[1] > 0) {
                content += `
                  <div>
                    <div>
                      ${params[i].marker}
                      <span style="font-size:14px;color:#666;font-weight:400;margin-left:2px">${params[i].seriesName}</span>
                      <span style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">${params[i].value[1] || '-'}</span>
                      <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                  </div>
                `;
              }
            }

            return header + (content !== '' ? content : '<span style="font-size:14px;">No data.</span>');
          },
        },
        legend: {
          show: this.legend,
          data: this.resultSet.map(item => item.name),
        },
        xAxis: {
          type: (!isNaN(date)) ? 'time' : 'category',
          boundaryGap: false,
          name: this.xtitle,
          nameLocation: 'middle',
          nameGap: 40,
          nameTextStyle: {
            color: '#303133',
            fontSize: 13,
          },
          axisLabel: {
            interval: this.resultSet[0].data.length > 20 ? 5 : 0,
            rotate: this.resultSet[0].data.length > 4 ? 20 : 0,
          },
        },
        yAxis: {
          type: 'value',
          name: this.ytitle,
          nameLocation: 'middle',
          nameGap: 40,
          nameTextStyle: {
            color: '#303133',
            fontSize: 13,
          },
        },
        series: this.resultSet.map(item => ({
          name: item.name,
          type: 'line',
          stack: 'Total',
          lineStyle: {
            width: 0,
          },
          showSymbol: false,
          areaStyle: {
            opacity: 0.8,
          },
          emphasis: {
            focus: 'series',
          },
          smooth: true,
          data: item.data,
        })),
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

<style>
.echarts-tooltip-custom-class {
  max-height: 300px!important;
  overflow-y: auto;
}
</style>
