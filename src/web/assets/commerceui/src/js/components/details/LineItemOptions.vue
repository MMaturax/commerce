<template>
    <div v-if="Object.keys(lineItem.options).length || editing" class="order-indented-block">
        <div class="order-flex">
            <div class="order-block-title">
                <h3>{{"Options"|t('commerce')}}</h3>
            </div>

            <div class="order-flex-grow">
                <template v-if="!editing">
                    <template v-if="Object.keys(lineItem.options).length">
                        <ul :id="'info-' + lineItem.id">
                            <template v-for="(option, key) in lineItem.options">
                                <li :key="'option-'+key">
                                    <code>
                                        {{key}}:

                                        <template v-if="Array.isArray(option)">
                                            <code>{{ option }}</code>
                                        </template>

                                        <template v-else>{{ option }}</template>
                                    </code>
                                </li>
                            </template>
                        </ul>
                    </template>
                </template>
                <template v-else>
                    <prism-editor v-model="options" language="js" @change="onOptionsChange"></prism-editor>

                    <ul v-if="errors.length > 0" class="errors">
                        <li v-for="(error, key) in errors" :key="key">{{error}}</li>
                    </ul>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import debounce from 'lodash.debounce'
    import PrismEditor from 'vue-prism-editor'

    export default {
        components: {
            PrismEditor,
        },

        props: {
            lineItem: {
                type: Object,
            },
            editing: {
                type: Boolean,
            },
        },

        data() {
            return {
                options: null,
                errors: [],
            }
        },

        watch: {
            lineItem() {
                if (this.lineItem) {
                    this.options = JSON.stringify(this.lineItem.options, null, '\t')
                }
            }
        },

        methods: {
            onOptionsChange() {
                this.errors = []
                let jsonValid = true
                let options = null

                try {
                    options = JSON.parse(this.options);
                } catch(e) {
                    jsonValid = false
                }

                if (jsonValid) {
                    this.onOptionsChangeWithValidJson(options)
                } else {
                    this.errors = ['Invalid JSON']
                }
            },

            onOptionsChangeWithValidJson: debounce(function(options) {
                const lineItem = this.lineItem
                lineItem.options = options
                this.$emit('updateLineItem', lineItem)
            }, 1000)
        },

        mounted() {
            this.options = JSON.stringify(this.lineItem.options, null, '\t')
        }
    }
</script>