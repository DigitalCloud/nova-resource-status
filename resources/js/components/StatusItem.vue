<template>
    <tr :dusk="resource['id'].value + '-row'">
        <!-- Resource Selection Checkbox -->
        <td
            :class="{
                'w-16': shouldShowCheckboxes,
                'w-8': !shouldShowCheckboxes,
            }"
        >

        </td>

        <!-- Fields -->
        <td v-for="field in resource.fields">
            <component
                :is="'index-' + field.component"
                :class="`text-${field.textAlign}`"
                :resource-name="resourceName"
                :via-resource="viaResource"
                :via-resource-id="viaResourceId"
                :field="field"
            />
        </td>
    </tr>

</template>

<script>
    import md5 from 'md5'
    import { Errors, InteractsWithResourceInformation } from 'laravel-nova'

    export default {

        mixins: [InteractsWithResourceInformation],

        props: [
            'testId',
            'deleteResource',
            'restoreResource',
            'resource',
            'resourcesSelected',
            'resourceName',
            'relationshipType',
            'viaRelationship',
            'viaResource',
            'viaResourceId',
            'viaManyToMany',
            'checked',
            'actionsAreAvailable',
            'shouldShowCheckboxes',
            'updateSelectionStatus',
        ],

        data: () => ({
            note: {},
            deleteModalOpen: false,
            restoreModalOpen: false,
            updateMode: false,
            lastRetrievedAt: null,
            value: null
        }),

        created() {
            this.note = this.resource.fields[0]
            this.value = this.resource.fields[0].value
            this.updateLastRetrievedAtTimestamp()
        },

        methods: {

            getField(name) {
                let fields = this.resource.fields.filter(field => field.attribute === name)
                return fields? fields[0] : null
            },



            updateNoteValue(g) {
                console.log('updateNoteValue');
            },

            async updateNote() {

                if(!this.updateMode) {
                    return this.updateMode = true
                }

                try {
                    const response = await this.updateRequest()
                    this.$nextTick(() => {
                        this.updateMode = false;
                    });

                } catch (error) {

                }
            },

            updateRequest() {
                return Nova.request().post(
                    `/nova-api/${this.resourceName}/${this.resource.id.value}`,
                    this.updateNoteFormData()
                )
            },

            updateNoteFormData() {
                var _this = this
                return _.tap(new FormData(), formData => {
                    this.note.fill(formData)
                    // formData.append('note', note.value)
                    formData.append('_method', 'PUT')
                    formData.append('_retrieved_at', Math.floor(new Date().getTime() / 1000))
                })
            },

            updateLastRetrievedAtTimestamp() {
                this.lastRetrievedAt = Math.floor(new Date().getTime() / 1000)
            },

            /**
             * Select the resource in the parent component
             */
            toggleSelection() {
                this.updateSelectionStatus(this.resource)
            },

            openDeleteModal() {
                this.deleteModalOpen = true
            },

            confirmDelete() {
                this.deleteResource(this.resource)
                this.closeDeleteModal()
            },

            closeDeleteModal() {
                this.deleteModalOpen = false
            },

            openRestoreModal() {
                this.restoreModalOpen = true
            },

            confirmRestore() {
                this.restoreResource(this.resource)
                this.closeRestoreModal()
            },

            closeRestoreModal() {
                this.restoreModalOpen = false
            },
        },

        computed: {
            gravatar() {
                return "https://secure.gravatar.com/avatar/" + md5(this.getField('creator').value.email)  + "?size=512"
            },
            currentMode() {
                return this.updateMode? 'note-text' : "index-note-text"
            }
        },
        watch: {
            note: function(val) {
                console.log('watch:note')
            }
        }
    }
</script>
