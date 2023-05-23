<template>
    <div v-if="toolbar.length" class="h-100% flex justify-start items-center">
        <n-tooltip v-for="(item, index) in toolbar" :key="index" :show="item.isUpdate" trigger="hover" placement="bottom">
            <template #trigger>
                <div @click="item.hanlder" class="item-container">
                    <AppIcons :icon="item.icon" :size="18" :color="item.isUpdate ? '#ff0000' : '#555'" />
                </div>
            </template>
            {{ item?.title ?? '未知' }}
        </n-tooltip>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // 是否全屏
            fullscreen: false,
            // 工具栏
            toolbar: [
                {
                    title: '全屏缩放',
                    name: 'zoom',
                    icon: 'ExpandOutlined',
                    hanlder: () => {
                        const element = document.documentElement
                        // 如果是全屏状态
                        if (this.fullscreen) {
                            // 如果浏览器有这个Function
                            if (document.exitFullscreen) {
                                document.exitFullscreen()
                            } else if (document.webkitCancelFullScreen) {
                                document.webkitCancelFullScreen()
                            } else if (document.mozCancelFullScreen) {
                                document.mozCancelFullScreen()
                            } else if (document.msExitFullscreen) {
                                document.msExitFullscreen()
                            }
                        } else {
                            // 如果浏览器有这个Function
                            if (element.requestFullscreen) {
                                element.requestFullscreen()
                            } else if (element.webkitRequestFullScreen) {
                                element.webkitRequestFullScreen()
                            } else if (element.mozRequestFullScreen) {
                                element.mozRequestFullScreen()
                            } else if (element.msRequestFullscreen) {
                                element.msRequestFullscreen()
                            }
                        }
                        // 判断全屏状态的变量
                        this.fullscreen = !this.fullscreen
                    },
                },
                // {
                //     title: '系统设置',
                //     name: 'settings',
                //     icon: 'SettingOutlined',
                //     hanlder: () => {
                //         console.log('系统设置');
                //     },
                // },
                {
                    title: '在线升级',
                    name: 'update',
                    icon: 'RocketOutlined',
                    hanlder: () => {
                        this.$routerApp.push('/Index/updateCheck')
                    },
                }
            ]
        }
    },
    mounted() {
        this.initify()
    },
    methods: {
        openUpdate() {
            const _this = this
            _this.toolbar.forEach((item, index) => {
                if (item.name === 'update') {
                    _this.toolbar[index] = {
                        ...item,
                        isUpdate: true,
                        title: '有新版本更新啦~',
                    }
                }
            })
        },
        checking() {
            const _this = this
            const currentRoute = _this.$routeApp.path
            // 已经在更新页面，不再重复请求
            if (currentRoute === '/Index/updateCheck') {
                return;
            }
            _this.$http.useDelete('admin/Index/updateCheck').then((res) => {
                const { data } = res
                // 存在忽略版本更新
                const ignoreVersion = localStorage.getItem('system_updated')
                if (ignoreVersion) {
                    if (parseInt(ignoreVersion) !== data.version) {
                        _this.openUpdate()
                    }
                } else {
                    _this.openUpdate()
                }
            })
        },
        initify() {
            const _this = this
            _this.checking()
        }
    },
}
</script>

<style lang="scss" scoped>
.item-container {
    height: 100%;
    padding: 0 0.8rem;
    display: flex;
    align-items: center;
    user-select: none;
    cursor: pointer;
}

.item-container:hover {
    background: #d5d5d5;
}
</style>