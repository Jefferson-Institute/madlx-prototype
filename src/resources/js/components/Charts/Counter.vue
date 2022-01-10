<template>
  <div class="number-chart" :style="{'color': background!='#FFFFFF'?'#FFFFFF':color}">
    <div class="number-chart-icon" :style="{'background-color': background?pSBC(-0.3, background):'#FFF', 'border-color': background!='#FFFFFF'?'transparent':'#E6EBF5'}">
      <i :class="icon?icon:'el-icon-s-marketing'" class-name="card-panel-icon" />
    </div>
    <div class="number-chart-data" :style="{'background-color': background?background:'#FFF'}">
      <div class="number-chart-text">{{ title }}</div>
      <div v-if="resultSet.series(pivotConfig).length">
        <count-to v-for="item in resultSet.series(pivotConfig)" :key="item.key" class="number-chart-number" :start-val="0" :end-val="item.series[0].value" :duration="2000" :decimals="0" :autoplay="true" />
      </div>
      <div v-else class="number-chart-number">
        <span>0</span>
      </div>
    </div>
  </div>
</template>

<script>
import countTo from 'vue-count-to';
export default {
  name: 'Counter',
  components: { countTo },
  props: {
    resultSet: {
      type: Object,
      required: true,
    },
    pivotConfig: {
      type: Object,
      required: true,
      default() {
        return {};
      },
    },
    title: {
      type: String,
      required: false,
      default: '- Chart Title -',
    },
    icon: {
      type: String,
      required: false,
      default: 'el-icon-s-marketing',
    },
    background: {
      type: String,
      required: false,
      default: '#FFFFFF',
    },
    color: {
      type: String,
      required: false,
      default: '#303133',
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
.number-chart {
  height: 100%;
  background: #fff;
  color: #303133;
  text-align: center;
  display: flex;
  align-items: stretch;
  justify-content: space-between;
  box-shadow: 0 2px 2px 0 rgb(0 0 0 / 13%), 0 3px 1px -2px rgb(0 0 0 / 19%), 0 1px 5px 0 rgb(0 0 0 / 11%);
}
.number-chart-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 0.4;
  border-right: 1px solid transparent;
  font-size: 98px;
}
.number-chart-data {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 0.6;
  flex-direction: column-reverse;
}
.number-chart-text {
  font-size: 18px;
}
.number-chart-number {
  font-size: 68px;
}
</style>
