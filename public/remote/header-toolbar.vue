<template>
    <div class="h-100%">
        <!-- 弹窗提示有版本更新 -->
        <Teleport to="body">
            <div class="updated-version" v-if="updateInfo.status">
                <div class="updated-box">
                    <img src="/image/updated-bg.png" class="updated-bg" alt="" />
                    <div class="updated-title">
                        发现新版本更新
                    </div>
                    <div class="updated-content-box">
                        <div class="version-box">
                            <div class="updated-version-title">
                                当前：{{ updateInfo.detail.client_version_name }}（{{updateInfo.detail.client_version}}）
                            </div>
                            <div class="updated-version-title">
                                下个版本：{{ updateInfo.detail.version_name }}（{{updateInfo.detail.version}}）
                            </div>
                        </div>
                        <pre class="updated-content">{{ updateInfo.detail.content }}</pre>
                    </div>
                    <div class="updated-buttons">
                        <button class="action-button to-btn" @click="toUpdated('/Updated/updateCheck')">立即去更新</button>
                        <button class="action-button cancel-btn" @click="hanldCancel">忽略本次更新</button>
                    </div>
                </div>
            </div>
        </Teleport>
        <!-- 工具栏 -->
        <div v-if="toolbar.length" class="h-100% flex justify-start items-center">
            <n-tooltip v-for="(item, index) in toolbar" :key="index" :show="item.isUpdate" trigger="hover"
                placement="bottom">
                <template #trigger>
                    <div @click="item.hanlder" class="item-container">
                        <AppIcons :icon="item.icon" :size="18" :color="item.isUpdate ? '#ff0000' : '#555'" />
                    </div>
                </template>
                {{ item?.title ?? '未知' }}
            </n-tooltip>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // 是否全屏
            fullscreen: false,
            // 是否有版本更新
            updateInfo: {
                status: false,
                detail: {
                    version: 0,
                    version_name: '',
                    content: '',
                    client_version: 0,
                    client_version_name: '',
                },
            },
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
                        this.$routerApp.push('/Updated/updateCheck')
                    },
                }
            ]
        }
    },
    mounted() {
        this.initify()
    },
    methods: {
        toUpdated(path){
            this.updateInfo.status = false
            this.$routerApp.push(path)
        },
        checking() {
            const _this = this
            const currentRoute = _this.$routeApp.path
            // 已经在更新页面，不再重复请求
            if (currentRoute === '/Updated/updateCheck') {
                return;
            }
            _this.$http.useDelete('admin/Updated/updateCheck').then((res) => {
                const { data } = res
                // 无版本升级
                if (data?.version <= data?.client_version) {
                    return;
                }
                // 存在忽略版本更新
                const ignoreVersion = localStorage.getItem('system_updated')
                if (ignoreVersion) {
                    if (parseInt(ignoreVersion) !== data.version) {
                        _this.openUpdate()
                    }
                } else {
                    // 有新版本，弹窗提示
                    _this.updateInfo = {
                        status: true,
                        detail: data
                    }
                }
            })
        },
        // 取消本次更新
        hanldCancel() {
            const _this = this;
            localStorage.setItem("system_updated", _this.updateInfo.detail.version);
            window.location.reload();
        },
        initify() {
            const _this = this
            _this.checking()
        }
    },
}
</script>

<style lang="scss">
.updated-version {
    position: fixed;
    inset: 0;
    background: rgba(#000, .4);
    -webkit-backdrop-filter: blur(6px);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;

    .updated-box {
        width: 350px;
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 2px 2px 10px rgba(#000, .1);

        .updated-bg {
            height: 100px;
            background: #722ED1;
        }

        .updated-title {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #333;
            font-weight: 700;
        }

        .updated-content-box {
            border-top: 1px solid #e5e5e5;
            border-bottom: 1px solid #e5e5e5;
            padding: 0 15px;
            height: 200px;

            .version-box {
                display: flex;
                gap: 10px;
                color: #333;
                font-size: 12px;
                padding: 5px 0;
            }

            .updated-content {
                font-size: 12px;
                color: #555;
            }
        }

        .updated-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 0;
            gap: 20px;

            .action-button {
                color: #fff;
                padding: 6px 15px;
                border-radius: 4px;
                font-size: 12px;
            }

            .to-btn {
                background: #722ED1;
            }

            .cancel-btn {
                background: #FF7D00;
            }
        }
    }
}

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