<template>
  <div class="h-100% app-container">
    <!-- 已授权列表 -->
    <div class="app-list">
      <div class="item" v-for="(item, index) in getAuthPlugins" :key="index">
        <div class="app-item">
          <img :src="item?.logo ?? ''" class="logo" alt="">
          <div class="button-group">
            <div class="action-button" @click="hanldStop(item?.name ?? '')">取消</div>
          </div>
          <div class="title">{{ item?.title }}</div>
        </div>
      </div>
      <div class="item action" @click="hanldShowDialog">
        <AppIcons icon="PlusOutlined" :size="48" color="#888" />
      </div>
    </div>
    <!-- 选择组件弹窗 -->
    <el-dialog v-bind="modalDialog" v-model="modalDialog.show">
      <div class="app-select-list">
        <div class="item" v-for="(item, index) in ajaxParams.plugin_list" :key="index">
          <div class="app-item" :class="{ 'active': modalDialogActive.includes(item.name) }"
            @click="hanldSelect(item?.name ?? '')">
            <img :src="item?.logo ?? ''" class="logo" alt="" />
            <div class="title">{{ item?.title ?? '错误' }}</div>
          </div>
        </div>
      </div>
      <template #footer>
        <div class="dialog-footer">
          <el-button type="primary" @click="hanldConfirm">确定</el-button>
          <el-button type="danger" @click="modalDialog.show = false">取消</el-button>
        </div>
      </template>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: Array,
    ajaxParams: {
      plugin_list: Array
    },
  },
  data() {
    return {
      modalDialogActive: [],
      modalDialog: {
        show: false,
        title: '选择已安装应用插件',
        width: '45%',
        closeOnClickModal:false,
      }
    }
  },
  computed: {
    // 获取已选择授权
    getAuthPlugins() {
      if (!this.ajaxParams?.plugin_list) {
        return [];
      }
      return this.ajaxParams.plugin_list.filter((item) => {
        if (this.modelValue.find((e) => e === item?.name)) {
          return item;
        }
      })
    },
  },
  methods: {
    // 确认选择
    hanldConfirm() {
      let active = this.modelValue;
      this.modalDialogActive.map(item => {
        if (!active.includes(item)) {
          active.push(item);
        }
      })
      this.$emit('update:modelValue', active);
      this.modalDialog.show = false;
    },
    // 设置选中
    hanldSelect(name) {
      if (this.modalDialogActive.length <= 0) {
        this.modalDialogActive.push(name);
        return;
      }
      if (!this.modalDialogActive.includes(name)) {
        this.modalDialogActive.push(name)
      }
    },
    // 显示模态框
    hanldShowDialog() {
      // 检测是否存在安装应用
      if (!this.ajaxParams?.plugin_list) {
          this.$useNotify("请先安装应用", 'error', '温馨提示')
        return;
      }
      this.modalDialog.show = true
      this.modalDialogActive = [];
      // 设置已授权选中
      this.modelValue.map(name => {
        this.modalDialogActive.push(name);
      })
    },
    // 删除选中
    hanldStop(name) {
      let actives = this.modelValue;
      for (let index = 0; index < actives.length; index++) {
        const item = actives[index];
        if (item === name) {
          actives.splice(index, 1);
        }
      }
      this.$emit('update:modelValue', actives);
    },
  },
}
</script>

<style lang="scss" scoped>
.app-container {
  display: flex;
  flex-direction: column;
  background: #fff;

  .app-list {
    flex: 1;
    display: flex;
    flex-wrap: wrap;

    .item {
      margin: 5px;

      .app-item {
        position: relative;
        width: 120px;
        height: 120px;
        border: 1px solid #d5d5d5;
        border-radius: 5px;

        .logo {
          width: 120px;
          height: 120px;
          border-radius: 5px;
        }

        .button-group {
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 32px;
          color: #fff;
          display: flex;
          justify-content: end;
          padding: 8px 10px;
          user-select: none;
          cursor: pointer;

          .action-button {
            display: none;
          }
        }

        .button-group:hover {
          background: rgba(#000000, 0.3);

          .action-button {
            display: block;
          }
        }

        .title {
          text-align: center;
          position: absolute;
          left: 0;
          right: 0;
          bottom: 0;
          padding: 5px 0;
          color: #fff;
          background: rgba(#000000, 0.5);
          user-select: none;
          border-bottom-left-radius: 5px;
          border-bottom-right-radius: 5px;
        }
      }
    }


    .action {
      width: 120px;
      height: 120px;
      border: 1px solid #e1e1e1;
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .action:hover {
      background: #f5f5f5;
    }
  }
}

.app-dialog {
  width: 45%;
  padding: 0;

  .n-dialog__title {
    padding: 10px 20px;
    border-bottom: 1px solid #d5d5d5;
  }

  .n-dialog__content {
    border-bottom: 1px solid #d5d5d5;
  }

  .n-dialog__action {
    padding: 0 0 15px 0;
    justify-content: center;
  }
}

.app-select-list {
  height: 420px;
  padding: 10px 15px 15px 15px;
  overflow-y: auto;
  overflow-x: hidden;
  display: flex;
  flex-wrap: wrap;

  .item {
    margin: 5px;

    .app-item {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border: 1px solid #d5d5d5;
      cursor: pointer;
      border-radius: 5px;
      width: 120px;
      height: 120px;

      .logo {
        width: 100%;
        height: 100px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
      }

      .title {
        font-size: 12px;
        line-height: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
      }
    }

    .active,
    .app-item:hover {
      border: 1px solid red;
    }
  }
}
</style>