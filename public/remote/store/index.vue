<template>
    <div class="app-container">
        <el-row :gutter="24">
            <el-col :md="24">
                <div class="user">
                    <div class="info">
                        <div class="avatar">
                            <el-avatar :size="51" :src="user?.headimg" />
                        </div>
                        <div class="nickname">
                            <div class="title">{{ user?.nickname }}</div>
                            <div class="date">{{ user?.role?.title }}</div>
                        </div>
                    </div>
                    <div class="platform">
                        <div class="item" v-for="(item, index) in platformApp" :key="index">
                            <div class="icon">
                                <el-image style="width:41px;height:41px;" :src="item.logo" />
                            </div>
                            <div class="content">
                                <div class="number">{{ item.created }}/{{ item.num }}</div>
                                <div class="title">{{ item.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
            <el-col :md="24">
                <div class="category">
                    <div class="tabs">
                        <el-tabs :model-value="projects.platformActive" @tab-change="hanldTabs">
                            <el-tab-pane :label="item.label" :name="item.key" v-for="(item, index) in platforms"
                                :key="index" />
                        </el-tabs>
                    </div>
                    <div class="created">
                        <el-button type="primary" class="develop-btn" color="#626aef" v-if="isDeveloper"
                            @click="hanldOepn('/Develop/create', { isBack: 1 })">
                            创建开发者项目
                        </el-button>
                        <el-dropdown @command="createPlatformProject">
                            <el-button type="primary">
                                新增项目
                            </el-button>
                            <template v-if="platformApp.length" #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item v-for="(item, index) in platformApp" :key="index" :command="item">
                                        {{ item.label }}
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </div>
                </div>
            </el-col>
            <el-col :md="24" v-if="projects.list.length">
                <div class="xh-project">
                    <div class="item" v-for="(item, index) in projects.list" :key="index">
                        <div @click="hanldAdmin(item)">
                            <div class="tags">
                                {{ item.platformTitle }}
                            </div>
                            <div class="icon">
                                <el-image style="width: 100%; height: 197px;border-radius: 3px;" :src="item.logo" />
                            </div>
                            <div class="title">
                                {{ item.title }}
                            </div>
                            <!-- <div class="desc">
                                基于视频号的第三方的独立分销平台
                            </div> -->
                        </div>
                        <div class="btns">
                            <el-button type="primary" size="small"
                                @click="hanldOepn('/StoreApp/edit', { id: item.id, isBack: 1 })">
                                编辑项目
                            </el-button>
                            <el-button type="danger" size="small" @click="hanldDel(item)">
                                删除项目
                            </el-button>
                        </div>
                    </div>
                </div>
            </el-col>
            <!-- 分页 -->
            <el-col :md="24" v-if="projects.list.length">
                <div class="pagination">
                    <el-pagination background layout="prev, pager, next" :total="projects.paginate.total" />
                </div>
            </el-col>
            <el-col :md="24" v-else>
                <div class="project-empty">
                    <el-empty description="当前没有更多的项目" />
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isDeveloper: false,
            user: {},
            platformApp: [],
            platforms: [
                {
                    label: '全部项目',
                    key: ''
                },
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
                paginate: {
                    page: 1,
                    limit: 10,
                    total: 0,
                },
                list: [],
            },
        }
    },
    methods: {
        // 创建项目
        createPlatformProject(e) {
            const detail = this.platforms.find((item) => item.key === e?.key);
            this.hanldOepn('/StoreApp/create', { platform: detail?.key, isBack: 1 });
        },
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
                    _this.$useNotify('操作数据失败', 'error', '温馨提示')
                }
            }).catch((err) => {
                _this.$useNotify(err?.msg ?? '操作数据失败', 'error', '温馨提示')
            })
        },
        // 跳转页面
        hanldOepn(path, item = {}) {
            this.$routerApp.push({
                path: path,
                query: item
            });
        },
        hanldTabs(name) {
            this.projects.platformActive = name;
            this.getList();
        },
        // 获取项目列表
        getList() {
            const _this = this;
            const params = {
                platform: _this.projects.platformActive,
            }
            _this.$http.useGet('store/StoreApp/index', params).then((res) => {
                // 列表
                _this.projects.list = res?.data?.data ?? [];
                // 分页
                _this.projects.paginate.total = res?.data?.total ?? 0;
                _this.projects.paginate.page = res?.data?.current_page ?? 1;
                _this.projects.paginate.limit = res?.data?.per_page ?? 10;
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
        this.user = this.$userApp.userInfo;
        this.getIndex();
        this.getList();
    }
};
</script>

<style lang="scss">
.xhadmin-header-pro {
    padding: 0 338px !important;
}

.xhadmin-main {
    padding: 20px 338px !important;
}

.el-tabs {
    .el-tabs__header {
        margin: 0;

        .el-tabs__nav-wrap::after {
            display: none;
        }
    }
}

.project-empty{
    background-color: #fff;
    padding:100px 0;
    border-radius:8px;
}

.pagination {
    padding: 20px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-end;
}

.xh-project {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    margin: 0px -10px;

    .item {
        position: relative;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        border-radius: 5px;
        width: 16%;
        min-width: 200px;
        overflow: hidden;
        cursor: pointer;
        transition: all .3s ease;
        margin: 0px 10px;

        .icon {
            width: 100%;
        }

        .desc {
            color: #666;
            font-size: 12px;
            min-height: 34px;
            /* 固定宽度 */
            width: 100%;
            /* 将溢出的部分隐藏 */
            overflow: hidden;
            /* 把盒子作为弹性盒子显示 */
            display: -webkit-box;
            /* 让子元素垂直排列 */
            -webkit-box-orient: vertical;
            /* 设置元素显示的行数 */
            -webkit-line-clamp: 2;
        }

        .title {
            color: rgba(0, 0, 0, .85);
            margin: 10px 0px;
            font-weight: bold;
        }

        .btns {
            margin: 10px 0px;
        }

        .tags {
            position: absolute;
            top: 0px;
            right: 0px;
            background-color: #67C23A;
            font-size: 8px;
            color: #fff;
            padding: 5px;
            border-radius: 0px 5px 0px 10px;
            z-index: 999;
        }
    }

    .item:hover {
        -webkit-transform: translateY(-4px) scale(1.02);
        -moz-transform: translateY(-4px) scale(1.02);
        -ms-transform: translateY(-4px) scale(1.02);
        -o-transform: translateY(-4px) scale(1.02);
        transform: translateY(-4px) scale(1.02);
        -webkit-box-shadow: 0 14px 24px rgba(0, 0, 0, .2);
        box-shadow: 0 14px 24px #0003;
        z-index: 999;
        border-radius: 6px
    }
}

.category {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0px;

    .created {
        .develop-btn {
            margin-right: 10px;
        }
    }
}

.user {
    background-color: #fff;
    border-radius: 5px;
    padding: 15px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;

    .platform {
        display: flex;
        flex-direction: row;
        align-items: center;

        .item {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 10px 0px;
            justify-content: center;

            .icon {
                padding-top: 15px;
            }

            .content {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 0px 10px;

                .number {
                    color: #252631;
                    font-size: 20px
                }

                .title {
                    color: #98a9bc;
                    font-size: 12px
                }
            }
        }
    }

    .info {
        display: flex;
        flex-direction: row;
        align-items: center;

        .nickname {
            margin-left: 10px;

            .title {
                font-weight: bolder;
            }

            .date {
                font-size: 12px;
                color: #606266;
            }
        }
    }
}
</style>