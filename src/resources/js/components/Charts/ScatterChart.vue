<template>
  <v-chart class="chart" :option="option" :autoresize="true" @click="mergeOptions" />
</template>

<script>
import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { ScatterChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  CanvasRenderer,
  ScatterChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent,
]);

export default {
  name: 'ScatterChart',
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
    url: {
      type: String,
      required: false,
      default: '',
    },
    fileName: {
      type: String,
      required: false,
      default: 'madlx-chart',
    },
  },
  computed: {
    option() {
      return {
        color: ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8'],
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              title: 'Save',
              show: true,
              name: this.fileName,
            },
          },
        },
        tooltip: {
          trigger: 'axis',
        },
        legend: {
          show: this.legend,
          data: this.resultSet.map(item => item.name),
        },
        xAxis: {
          show: false,
          type: 'category',
          name: this.xtitle,
          nameLocation: 'middle',
          nameGap: 40,
          nameTextStyle: {
            color: '#303133',
            fontSize: 13,
          },
          axisLabel: {
            interval: this.resultSet[0].data.length > 10 ? 5 : 0,
            rotate: 20,
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
          symbolSize: 20,
          name: item.name,
          type: 'scatter',
          areaStyle: this.area ? {} : null,
          smooth: this.curve,
          data: item.data,
        })),
      };
    },
  },
  methods: {
    mergeOptions(eventInfo) {
      this.$router.push('/' + this.url + '/' + eventInfo.data[0]);
    },
  },
};
</script>

<style scoped>
.chart {
  height: 400px;
}
</style>
