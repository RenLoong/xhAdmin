<template>
  <div class="h-100% app-container">
    <!-- 已授权列表 -->
    <n-grid :cols="6" :y-gap="22" :x-gap="8" class="app-list">
      <n-grid-item class="item" v-for="(item, index) in getAuthPlugins" :key="index">
        <div class="app-item">
          <img :src="item?.logo ?? ''" class="logo" alt="">
          <div class="button-group">
            <div class="action-button" @click="hanldStop(item?.name ?? '')">取消</div>
          </div>
          <div class="title">{{ item?.title }}</div>
        </div>
      </n-grid-item>
      <n-grid-item class="action" @click="hanldShowDialog">
        <AppIcons icon="PlusOutlined" :size="48" color="#888" />
      </n-grid-item>
    </n-grid>
    <!-- 选择组件弹窗 -->
    <n-modal v-model:show="modalDialog.show" v-bind="modalDialog" @PositiveClick="hanldConfirm">
      <n-grid :cols="6" :y-gap="22" :x-gap="8" class="app-select-list">
        <n-grid-item class="item" v-for="(item, index) in ajaxParams.plugin_list" :key="index">
          <div class="app-item" :class="{ 'active': modalDialogActive.find((name) => item?.name === name) }"
            @click="hanldSelect(item?.name ?? '')">
            <img :src="item?.logo ?? ''" class="logo" alt="" />
            <div class="title">{{ item?.title ?? '错误' }}</div>
          </div>
        </n-grid-item>
      </n-grid>
    </n-modal>
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
        maskClosable: false,
        preset: 'dialog',
        title: '选择已安装应用插件',
        icon: () => undefined,
        showIcon: false,
        to: '.app-container',
        class: 'app-dialog',
        positiveText: '确定',
        negativeText: '取消',
      }
    }
  },
  computed: {
    // 获取已选择授权
    getAuthPlugins() {
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
      const _this = this;
      let actives = [];
      for (let index = 0; index < _this.modalDialogActive.length; index++) {
        const name = _this.modalDialogActive[index];
        const item = _this.modelValue.find((e) => e === name);
        if (!item) {
          actives.push(name);
        }
      }
      this.$emit('update:modelValue', actives);
    },
    // 设置选中
    hanldSelect(name) {
      if (this.modalDialogActive.length <= 0) {
        this.modalDialogActive.push(name);
        return;
      }
      this.modalDialogActive.forEach((item, index) => {
        if (item === name) {
          this.modalDialogActive.splice(index, 1);
        } else {
          this.modalDialogActive.push(name)
        }
      });
    },
    // 显示模态框
    hanldShowDialog() {
      this.modalDialog.show = true
      this.modalDialogActive = [];
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

    .item {
      .app-item {
        position: relative;
        width: 120px;
        height: 120px;
        border: 1px solid #d5d5d5;
        border-radius: 5px;

        .logo {
          width:120px;
          height:120px;
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
  height: 480px;
  padding: 10px 15px 15px 15px;
  overflow-y: auto;
  overflow-x: hidden;

  .item {
    .app-item {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border: 1px solid #d5d5d5;
      cursor: pointer;
      border-radius: 5px;

      .logo {
        width: 100%;
        height: 100px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
      }

      .title {
        padding: 3px 0;
      }
    }

    .active,
    .app-item:hover {
      border: 1px solid red;
    }
  }
}
</style>