<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { RadarChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  CanvasRenderer,
  RadarChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
]);

export default {
  name: 'RadarChart',
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
    fileName: {
      type: String,
      required: false,
      default: 'madlx-chart',
    },
  },
  data: () => ({
    option: null,
  }),
  mounted() {
    this.getOptions();
  },
  methods: {
    getOptions() {
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

      const niz = {};

      data.forEach((item, key) => {
        if (item[headers[1].text] === null) {
          return;
        }
        if (!(item[headers[0].text] in niz)) {
          niz[item[headers[0].text]] = [];
        }
        niz[item[headers[0].text]].push(item[headers[headers.length - 1].text]);
      });

      const finalData = [];
      for (const property in niz) {
        finalData.push({ name: property, value: niz[property] });
      }

      this.option = {
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
        color: ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8'],
        tooltip: {
          trigger: 'item',
        },
        legend: {
          data: finalData.map(i => i.name),
        },
        radar: {
          // shape: 'circle',
          indicator: unique.map(i => ({ name: i })),
        },
        series: [{
          type: 'radar',
          areaStyle: {},
          data: finalData,
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
