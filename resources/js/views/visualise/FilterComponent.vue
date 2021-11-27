<template>
  <div>
    <el-button type="primary" plain @click="dialog = true">Filters</el-button>

    <el-dialog
      title="Filters"
      :visible.sync="dialog"
      max-width="30%"
    >
      <el-row>
        <el-col>
          <template>
            <h5
              style="margin: 10px 2px; padding: 0; font-weight: 300"
            >
              Active Filters:
            </h5>
            <ul v-if="filters.length" style="list-style-type: none; padding: 0; margin:0px ">
              <li
                v-for="(filter, index) in filters"
                :key="index"
                style="
                  display: inline-block;
                  background: #fff;
                  border: 1px solid #dcdfe6;
                  padding: 8px 15px;
                  color: #1f8efa;
                "
              >
                <div class="mx-auto filter-card" :style="{color: filter['fixed']?'#303133':'#1f8efa'}">
                  <div>
                    {{ !filter['fixed'] ? filter['member']['title'] : 'Only Learners Filter' }}
                    <i
                      v-if="!filter['fixed']"
                      class="el-icon-delete"
                      @click="removeFilter(index, filter)"
                    />
                  </div>
                </div>
              </li>
            </ul>
            <p v-if="!filters.length">None</p>
          </template>
        </el-col>
      </el-row>
      <el-row :gutter="40">
        <el-col>
          <h5
            style="margin: 30px 2px 10px; padding: 0; font-weight: 300"
          >
            Add Custom Filter:
          </h5>
        </el-col>
        <el-col :xs="12" :sm="12" :lg="8">
          <el-select
            v-model="select"
            placeholder="Select Dimension or Measure"
            :style="{ width: '100%', 'margin-bottom': '10px' }"
          >
            <el-option
              v-for="item in [...measures, ...dimensions]"
              :key="item"
              :label="item"
              :value="item"
            />
          </el-select>
        </el-col>
        <el-col v-if="select" :xs="12" :sm="12" :lg="8">
          <el-select
            v-model="operator"
            placeholder="Select action"
            :style="{ width: '100%', 'margin-bottom': '10px' }"
          >
            <el-option
              v-for="item in actionItems"
              :key="item.value"
              :label="item.text"
              :value="item.value"
            />
          </el-select>
        </el-col>
        <el-col v-if="operator" :xs="12" :sm="12" :lg="8">
          <el-input
            v-model="value"
            placeholder="Value"
            :style="{ width: '100%', 'margin-bottom': '10px' }"
          />
        </el-col>
        <el-col>
          <el-button type="primary" @click="save">
            Add filter
          </el-button>
        </el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'FilterComponent',
  props: ['filters', 'dimensions', 'measures', 'setFilters'],
  data: () => ({
    dialog: false,
    form: false,
    select: '',
    operator: '',
    value: '',
    actionItems: [
      {
        text: 'equals',
        value: 'equals',
      },
      {
        text: 'does not equal',
        value: 'notEquals',
      },
      {
        text: 'is set',
        value: 'set',
      },
      {
        text: 'is not set',
        value: 'notSet',
      },
      {
        text: '>',
        value: 'gt',
      },
      {
        text: '>=',
        value: 'gte',
      },
      {
        text: '<',
        value: 'lt',
      },
      {
        text: '<=',
        value: 'lte',
      },
    ],
  }),
  created() {
    // this.addOnlyLearners();
  },
  methods: {
    // addOnlyLearners() {
    //   const newFilters = [
    //     {
    //       member: 'Statements.actorMbox',
    //       operator: 'equals',
    //       values: this.learners,
    //       fixed: true,
    //     },
    //     ...this.filters.map((v) => ({
    //       member: v.member.name,
    //       operator: v.operator,
    //       values: v.values,
    //       fixed: v.fixed || false,
    //     })),
    //   ];
    //   this.setFilters(newFilters);
    // },
    save() {
      this.form = false;
      const newFilters = [
        ...this.filters.map((v) => ({
          member: v.member.name,
          operator: v.operator,
          values: v.values,
          fixed: v.fixed || false,
        })),
        { member: this.select, operator: this.operator, values: [this.value] },
      ];
      this.setFilters(newFilters);
      this.clearFilter();
    },
    removeFilter(index, filter) {
      if (filter.member.name === 'Statements.authorityName' && filter.operator === 'equals') {
        this.$notify({
          title: 'Info',
          message: 'Cannot remove Authority Filter, use Dashboard dropdown',
          type: 'info',
          duration: 3000,
        });
        return;
      }
      this.filters.splice(index, 1);
      const newFilters = [
        ...this.filters.map((v) => ({
          member: v.member.name,
          operator: v.operator,
          values: v.values,
          fixed: v.fixed || false,
        })),
      ];
      this.setFilters(newFilters);
    },
    clearFilter() {
      this.select = '';
      this.operator = '';
      this.value = '';
    },
  },
};
</script>
