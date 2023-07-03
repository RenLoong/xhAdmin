<template>
    <div class="box-container">
        <n-form>
            <n-grid x-gap="12" :cols="2">
                <n-gi>
                    <n-form-item label="构建至应用">
                        <n-select :options="plugins" :on-update:value="hanldSelectApp" />
                    </n-form-item>
                </n-gi>
                <n-gi>
                    <n-form-item label="构建至菜单">
                        <n-cascader :options="menus" @update:value="hanldSelectMenu" />
                    </n-form-item>
                </n-gi>
            </n-grid>
        </n-form>
        <div class="ctrl-container" v-if="menusPreView">
            <n-divider>生成菜单预览</n-divider>
            <HCode v-model="menusPreView" language="php" />
        </div>
        <div class="ctrl-container" v-if="curdPreView.controllerPath">
            <n-divider>控制器代码预览</n-divider>
            <HCode v-model="curdPreView.controller" language="php" />
        </div>
        <div class="model-container" v-if="curdPreView.modelPath">
            <n-divider>模型代码预览</n-divider>
            <HCode v-model="curdPreView.model" language="php" />
        </div>
        <div class="validate-container" v-if="curdPreView.validatePath">
            <n-divider>验证器代码预览</n-divider>
            <HCode v-model="curdPreView.validate" language="php" />
        </div>
        <div class="button">
            <n-button type="primary" @click="submit">开始生成</n-button>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        ajaxParams: {}
    },
    data() {
        return {
            plugins: [],
            menus: [],
            menusPreView: '',
            curdPreView: {
                controllerPath: '',
                modelPath: '',
                validatePath: '',
                controller: '',
                model: '',
                validate: '',
            },
            formData: {
                app_name: '',
                menu_id: '',
            },
        }
    },
    created() {
        this.initify();
    },
    methods: {
        submit() {
            const _this = this;
            const params = {
                TABLE_NAME: this.ajaxParams.TABLE_NAME,
                app_name: this.formData.app_name,
                menu_id: this.formData.menu_id,
            }
            this.$http.usePost('admin/Curd/add', params).then((res) => {
                _this.$emit("update:closeWin");
                _this.$useNotification?.success({
                    title: res?.msg ?? "操作成功",
                    duration: 1500,
                });
            })
        },
        hanldSelectMenu(value) {
            this.formData.menu_id = value;
            this.getCurdPreView();
        },
        hanldSelectApp(value) {
            this.formData.app_name = value;
            this.getPluginMenus(value);
        },
        getCurdPreView() {
            const _this = this;
            const params = {
                TABLE_NAME: this.ajaxParams.TABLE_NAME,
                app_name: this.formData.app_name,
                menu_id: this.formData.menu_id
            }
            this.$http.useGet('admin/Curd/getPreview', params).then((res) => {
                _this.menusPreView = res.data?.menus
                _this.curdPreView = res.data?.code;
            })
        },
        getPlugins() {
            const params = {
                TABLE_NAME: this.ajaxParams.TABLE_NAME,
            }
            this.$http.useGet('admin/Curd/detail', params).then((res) => {
                this.plugins = res.data;
            })
        },
        getPluginMenus(app_name) {
            const params = {
                TABLE_NAME: this.ajaxParams.TABLE_NAME,
                app_name: app_name,
            }
            this.$http.useGet('admin/Curd/getPluginMenus', params).then((res) => {
                this.menus = res.data;
            })
        },
        initify() {
            this.getPlugins();
        }
    }
}
</script>
<style lang="scss">
.box-container {
    height: 100%;
    padding: 20px;
    overflow-y: auto;
    overflow-x: hidden;

    .button {
        display: flex;
        justify-content: center;
    }
}
</style>