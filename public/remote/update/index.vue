<template>
    <div class="update-container">
        <div class="title">{{ updated.title }}</div>
        <n-form labn-position="left" class="form-container" v-if="loading.code === 200">
            <n-form-item label="版本控制">
                本地版本：{{ updated.client_version_name }}（最新版本：{{ updated.version_name }}）
            </n-form-item>
            <n-form-item label="更新内容">
                <n-input type="textarea" :value="updated.content" :autosize="{ minRows: 6, maxRows: 10 }" placeholder="无"
                    disabled />
            </n-form-item>
            <n-form-item label="更新文件">
                <n-input type="textarea" :value="updated.chanage_files" :autosize="{ minRows: 6, maxRows: 10 }"
                    placeholder="无" disabled />
            </n-form-item>
            <div class="action-button">
                <n-button type="success" @click="hanldUpdate">立即更新</n-button>
                <n-button type="warning" @click="hanldCancel">忽略更新</n-button>
            </div>
        </n-form>
        <div v-else>
            <n-empty :show-description="false">
                <template #icon>
                    <AppIcons icon="CheckCircleOutlined" :size="48" color="#18a058" />
                </template>
                <template #extra="">
                    <div class="mt-5">
                        {{ loading.text }}
                    </div>
                </template>
            </n-empty>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: {
                text: '',
                code: 0
            },
            updated: {
                title: '',
                version_name: '',
                version: '',
                client_version_name: '',
                client_version: '',
                content: '',
            },
        }
    },
    mounted() {
        this.initify()
    },
    methods: {
        // 提交更新
        hanldUpdate() {
            const _this = this
            _this.$useDialog.create({
                type: 'warning',
                title: '温馨提示',
                content: '是否确定更新框架系统？请自行备份数据库与代码文件',
                positiveText: '确定',
                negativeText: '取消',
                maskClosable: false,
                onPositiveClick() {
                    const data = {
                        version: _this.updated.version
                    }
                    _this.$http.usePost('admin/Index/updateCheck', data).then((res) => {
                        _this.$useNotification?.success({
                            title: res?.msg ?? '操作成功',
                            duration: 1500,
                        });
                    })
                }
            });
        },
        // 取消本次更新
        hanldCancel() {
            const _this = this
            _this.$useDialog.create({
                type: 'warning',
                title: '温馨提示',
                content: '是否确认忽略本次版本更新？下个版本依然继续提示更新',
                positiveText: '确定',
                negativeText: '取消',
                maskClosable: false,
                onPositiveClick() {
                    localStorage.setItem('system_updated', _this.updated.version)
                    location.reload()
                }
            });
        },
        initify() {
            const _this = this
            _this.$http.usePut('admin/Index/updateCheck').then((res) => {
                const { data } = res
                _this.updated = data
                _this.loading.code = res?.code ?? 200
            }).catch((err) => {
                _this.loading.code = err?.code ?? 404
                _this.loading.text = err?.msg ?? '获取失败'
            })
        }
    },
}
</script>

<style lang="scss" scoped>
.update-container {
    background: #fff;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
    overflow-x: hidden;

    .title {
        text-align: center;
        font-size: 20px;
        font-weight: 700;
    }

    .form-container {
        margin-top: 20px;
        width: 60%;

        .action-button {
            display: flex;
            gap: 80px;
            align-items: center;
            justify-content: center;
        }
    }
}
</style>