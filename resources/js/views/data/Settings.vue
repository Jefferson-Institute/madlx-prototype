<template>
  <div class="app-container">
    <el-row type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;font-weight:600">
            <span>LRS Settings</span>
          </div>
          <el-form label-width="100px">
            <el-form-item label="Endpoint">
              <el-input v-model="lrs.endpoint" disabled />
            </el-form-item>
            <el-form-item label="Username">
              <el-input v-model="lrs.username" disabled />
            </el-form-item>
            <el-form-item label="Password">
              <el-input v-model="lrs.password" disabled />
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'Settings',
  data() {
    return {
      lrs: {
        endpoint: 'https://demo-lrs.madlx.org/trax/api/38a17443-52df-4fcf-a349-138d9af73f37/xapi/std/',
        username: 'hR14chsgz0eVFYaW12qUSA',
        password: 'djEwpqoEIYEklkZr5QAnpQ',
      },
    };
  },
  created() {
  },
  methods: {
  },
};
</script>

<style lang="scss" scoped>

</style>
