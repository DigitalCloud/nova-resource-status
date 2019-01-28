<template>
    <dce-statuses-wrapper
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="field.hasManyRelationship"
        :relationship-type="'hasMany'"
        @actionExecuted="actionExecuted"
        :load-cards="false"
    />
</template>

<script>
import StatusesWrapper from "./StatusesWrapper";
export default {
    components: {StatusesWrapper},
    props: ['resourceName', 'resourceId', 'resource', 'field'],

    methods: {
        /**
         * Handle the actionExecuted event and pass it up the chain.
         */
        actionExecuted() {
            this.$emit('actionExecuted')
        },
    },
}
</script>
