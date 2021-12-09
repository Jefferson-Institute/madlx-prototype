<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { SVGRenderer } from 'echarts/renderers';
import { SunburstChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  SVGRenderer,
  SunburstChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
]);

export default {
  name: 'SunburstChart',
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
    title: {
      type: String,
      required: false,
      default: '',
    },
    legend: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  computed: {
    option() {
      const datax = this.resultSet;
      const final = {};

      datax.forEach((item) => {
        var slider = final;
        for (const property in item) {
          if (typeof item[property] === 'string' || item[property] instanceof String){
            const value = item[property].trim();
            if (!(value in slider)) {
              slider[value] = {};
            }
            slider = slider[value];
          } else {
            slider['value'] = item[property];
          }
        }
      });

      // let backgroundColor = '#a6cd39'
      // color: '#8dd7f8'
      const iterate = (obj, colors) => {
        const niz = [];
        let counter = -1;
        for (const property in obj) {
          counter++;
          const parent = {
            name: property,
            itemStyle: {
              color: Array.isArray(colors) ? colors[counter] : colors,
            },
          };
          if (typeof obj[property] === 'object') {
            parent.children = iterate(obj[property], this.pSBC(-0.2, Array.isArray(colors) ? colors[counter] : colors));
          } else {
            parent.value = obj[property];
          }
          niz.push(parent);
        }
        return niz;
      };

      return {
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              title: 'Save',
              show: true,
            },
          },
        },
        series: {
          type: 'sunburst',
          data: iterate(final, ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8']),
          label: {
            color: '#000',
            fontSize: 14,
            formatter: function(param) {
              if (param.name === 'value') {
                return param.value.toFixed(0);
              }
              if (/\s/g.test(param.name)) {
                return param.name.split(' ').map(function(item){
                  return item[0];
                }).join('');
              }
            },
          },
          radius: [0, '100%'],
          sort: null,
          emphasis: {
            focus: 'ancestor',
          },
          itemStyle: {
            borderRadius: 4,
            borderWidth: 2,
          },
        },
      };
    },
  },
  methods: {
    pSBC(p, c0, c1, l){
      if (c0 === '#FFFFFF') {
        return c0;
      }
      let r, g, b, P, f, t, h, a = typeof (c1) === 'string';
      const m = Math.round, i = parseInt;
      if (typeof (p) !== 'number' || p < -1 || p > 1 || typeof (c0) !== 'string' || (c0[0] !== 'r' && c0[0] !== '#') || (c1 && !a)) {
        return null;
      }
      if (!this.pSBCr) {
        this.pSBCr = (d) => {
          let n = d.length;
          const x = {};
          if (n > 9){
            [r, g, b, a] = d = d.split(',');
            n = d.length;
            if (n < 3 || n > 4) {
              return null;
            }
            x.r = i(r[3] === 'a' ? r.slice(5) : r.slice(4));
            x.g = i(g);
            x.b = i(b);
            x.a = a ? parseFloat(a) : -1;
          } else {
            if (n === 8 || n === 6 || n < 4) {
              return null;
            }
            if (n < 6){
              d = '#' + d[1] + d[1] + d[2] + d[2] + d[3] + d[3] + (n > 4 ? d[4] + d[4] : '');
            }
            d = i(d.slice(1), 16);
            if (n === 9 || n === 5){
              x.r = d >> 24 & 255;
              x.g = d >> 16 & 255;
              x.b = d >> 8 & 255;
              x.a = m((d & 255) / 0.255) / 1000;
            } else {
              x.r = d >> 16;
              x.g = d >> 8 & 255;
              x.b = d & 255;
              x.a = -1;
            }
          } return x;
        };
      }
      h = c0.length > 9;
      h = a ? c1.length > 9 ? true : c1 === 'c' ? !h : false : h;
      f = this.pSBCr(c0);
      P = p < 0;
      t = c1 && c1 !== 'c' ? this.pSBCr(c1) : P ? { r: 0, g: 0, b: 0, a: -1 } : { r: 255, g: 255, b: 255, a: -1 };
      p = P ? p * -1 : p;
      P = 1 - p;
      if (!f || !t) {
        return null;
      }
      if (l){
        r = m(P * f.r + p * t.r);
        g = m(P * f.g + p * t.g);
        b = m(P * f.b + p * t.b);
      } else {
        r = m((P * f.r ** 2 + p * t.r ** 2) ** 0.5);
        g = m((P * f.g ** 2 + p * t.g ** 2) ** 0.5);
        b = m((P * f.b ** 2 + p * t.b ** 2) ** 0.5);
      }
      a = f.a;
      t = t.a;
      f = a >= 0 || t >= 0;
      a = f ? a < 0 ? t : t < 0 ? a : a * P + t * p : 0;
      if (h) {
        return 'rgb' + (f ? 'a(' : '(') + r + ',' + g + ',' + b + (f ? ',' + m(a * 1000) / 1000 : '') + ')';
      } else {
        return '#' + (4294967296 + r * 16777216 + g * 65536 + b * 256 + (f ? m(a * 255) : 0)).toString(16).slice(1, f ? undefined : -2);
      }
    },
  },
};
</script>

<style scoped>
.chart {
  height: 800px;
}
</style>
