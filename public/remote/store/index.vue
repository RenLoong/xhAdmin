<template>
    <div class="page-container">
        <!-- 头部 -->
        <div class="header-container">
            <div class="left-title">项目数据统计</div>
            <div class="right-tip">
                <AppIcons icon="QuestionCircleOutlined" :size="16" />
                <span class="text-tip">点击看板块，可创建对应的项目</span>
            </div>
        </div>
        <!-- 项目操作块 -->
        <div class="project-block">
            <div class="item-block" v-for="(item, index) in platformApp" :key="index"
                @click="hanldOepn('/StoreApp/create', { platform: item.key, isBack: 1 })">
                <div class="logo-container">
                    <img :src="item.logo" class="logo-block">
                </div>
                <div class="project-block-info">
                    <div class="project-block-title">{{ item.label }}</div>
                    <div class="project-block-num">{{ item.created }} / {{ item.num }}个</div>
                </div>
            </div>
        </div>
        <!-- 项目看板 -->
        <div class="project-content-container">
            <div class="project-title-container">
                <div class="project-title">
                    项目看板
                </div>
                <div class="project-action">
                    <button class="action-btn create-project" v-if="isDeveloper" @click="hanldOepn('/Develop/create')">
                        <AppIcons icon="Compass" type="element" :size="16" />
                        <span class="title">创建开发者项目</span>
                    </button>
                    <el-dropdown @command="handleSelectPlatform">
                        <button class="action-btn all-project">
                            <AppIcons icon="CaretDownOutlined" :size="16" />
                            <span class="title">{{ selectPlatform }}</span>
                        </button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item v-for="(item, index) in platforms" :key="index" :command="item">
                                    {{ item.label }}
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </div>
            </div>
            <div class="project-list" v-if="projects.list.length">
                <div class="project-item" v-for="(item, index) in projects.list" :key="index">
                    <img :src="item.logo" class="logo" />
                    <div class="pro-title-container">
                        <div class="pro-title">{{ item.title }}</div>
                        <div class="pro-type">
                            <el-tag :type="projects.paltformType[item.platform]">
                                {{ item.platformTitle }}
                            </el-tag>
                        </div>
                    </div>
                    <div class="pro-action-container">
                        <div class="action-item" @click="hanldAdmin(item)">
                            登录后台
                        </div>
                        <div class="action-item" @click="hanldOepn('/StoreApp/edit', { id: item.id, isBack: 1 })">
                            编辑项目
                        </div>
                        <div class="action-item" @click="hanldDel(item)">
                            删除项目
                        </div>
                    </div>
                </div>
            </div>
            <div class="project-empty" v-else>
                <el-empty description="当前没有更多的项目" />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isDeveloper: false,
            platformApp: [],
            platforms: [
                {
                    label: '全部项目',
                    key: ''
                },
            ],
            // 支持小程序工具栏显示
            appletType: [
                'mini_wechat',
                'douyin'
            ],
            projects: {
                platformActive: '',
                paltformType: {
                    'wechat': 'success',
                    'mini_wechat': 'success',
                    'douyin': 'warning',
                    'h5': 'danger',
                    'app': '',
                    'other': 'info',
                },
                list: [],
            },
        };
    },
    computed: {
        selectPlatform() {
            const { platformActive } = this.projects;
            const platform = this.platforms[platformActive];
            return platform ? platform.label : '全部项目';
        },
    },
    methods: {
        // 执行删除项目
        actionDelProject(e) {
            const _this = this;
            const { id } = e;
            _this.$http.useDelete('store/StoreApp/del', { id }).then((res) => {
                _this.platformActive = ''
                _this.getIndex();
                _this.getList();
                _this.$useNotify(res?.msg ?? "操作成功", 'success', '温馨提示')
            })
        },
        // 删除项目
        hanldDel(e) {
            const _this = this;
            _this.$useConfirm('是否确定删除该项目？', '温馨提示', 'error').then(() => {
                _this.actionDelProject(e);
            })
        },
        // 跳转项目后台
        hanldAdmin(e) {
            const _this = this;
            const params = {
                appid_id: e.id,
            }
            _this.$http.usePost('store/StoreApp/login', params).then((res) => {
                if (res.code === 200) {
                    const { data } = res;
                    if (data?.url) {
                        window.open(data?.url)
                    } else {
                        _this.$useNotify('登录项目管理失败', 'error', '温馨提示')
                    }
                } else {
                    _this.$useNotify('获取数据失败', 'error', '温馨提示')
                }
            })
        },
        // 选择平台
        handleSelectPlatform(e) {
            const platformIndex = this.platforms.findIndex((item) => item.key === e?.key);
            this.projects.platformActive = platformIndex;
            this.getList();
        },
        // 跳转页面
        hanldOepn(path, item = {}) {
            this.$routerApp.push({
                path: path,
                query: item
            });
        },
        // 获取项目列表
        getList() {
            const _this = this;
            const platform = _this.platforms[_this.projects.platformActive];
            const params = {
                platform: platform?.key,
            }
            _this.$http.useGet('store/StoreApp/index', params).then((res) => {
                const { data } = res;
                _this.projects.list = data;
            })
        },
        // 获取首页数据
        getIndex() {
            const _this = this;
            _this.$http.useGet('store/Index/indexData').then((res) => {
                const { data } = res;
                const { platformApp, isDeveloper } = data;
                _this.isDeveloper = isDeveloper;
                _this.platformApp = platformApp
                _this.platforms.push(...platformApp);
            })
        },
    },
    mounted() {
        this.getIndex();
        this.getList();
    }
}
</script>
<style lang="scss" scoped>
.page-container {
    height: 100%;
    display: flex;
    flex-direction: column;

    // 头部
    .header-container {
        background: #fff;
        height: 50px;
        display: flex;
        justify-content: space-between;
        padding: 0 20px;

        .left-title {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .right-tip {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #006EFF;

            .text-tip {
                padding-left: 3px;
            }
        }
    }

    // 项目操作块
    .project-block {
        display: flex;
        gap: 20px;

        .item-block {
            display: flex;
            background: #fff;
            margin-top: 10px;
            padding: 10px;
            width: 16%;
            user-select: none;
            cursor: pointer;
            border-radius: 5px;

            &:hover {
                background: #f9f9f9;
            }

            .logo-container {
                display: flex;
                align-items: bottom;
                .logo-block {
                    width: 80px;
                    height: 80px;
                }
            }

            .project-block-info {
                padding-left: 15px;
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;

                .project-block-title {
                    font-size: 16px;
                }

                .project-block-num {
                    font-size: 20px;
                    padding-top: 5px;
                    font-weight: 700;
                }
            }
        }
    }

    // 项目看板
    .project-content-container {
        flex: 1;
        background: #fff;
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;

        .project-title-container {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;

            .project-title {
                font-size: 20px;
            }

            .project-action {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 20px;

                .action-btn {
                    padding: 6px 10px;
                    border-radius: 5px;
                    background: #fff;
                    cursor: pointer;
                    transition: all .3s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: none;

                    .title {
                        padding-left: 2px;
                    }

                    &:hover {
                        color: #fff;
                    }
                }

                .all-project {
                    border: 1px solid #F53F3F !important;
                    color: #F53F3F;

                    &:hover {
                        background: #F53F3F;
                    }
                }

                .create-project {
                    border: 1px solid #722ED1;
                    color: #722ED1;

                    &:hover {
                        background: #722ED1;
                    }
                }
            }
        }

        .project-list {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            overflow-y: auto;
            overflow-x: hidden;

            .project-item {
                width: 230px;
                margin: 0 80px 10px 0;

                .logo {
                    display: block;
                    width: 100%;
                    height: 150px;
                    border-radius: 5px;
                    border: solid 1px #f0f0f0;
                    object-fit: cover;
                }

                .pro-title-container {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    padding-left: 8px;

                    .pro-title {
                        flex: 1;
                        font-size: 16px;
                        font-weight: 700;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .pro-type {
                        padding-left: 10px;
                    }
                }

                .pro-action-container {
                    display: flex;
                    cursor: pointer;
                    padding: 8px 0;

                    .action-item {
                        padding: 0 8px;
                        border-radius: 5px;
                        transition: all .3s;
                        color: #4b90eb;
                        font-size: 12px;
                        position: relative;

                        &:hover {
                            color: #016EFF;
                            background: #f9f9f9;
                        }

                        &::after {
                            position: absolute;
                            right: 0px;
                            top: 25%;
                            content: '';
                            display: block;
                            width: 1px;
                            height: 50%;
                            background: #000;
                        }
                    }

                    .action-item:last-child::after {
                        display: none;
                    }
                }
            }
        }

        .project-empty {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
}
</style>