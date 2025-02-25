<template>
  <k-inside>
    <k-header>Workshop Choice Ranking</k-header>
    <div class="k-choices-wrap">
      <div class="k-table">
      <table class="k-choices">
        <tr>
          <th data-mobile="true" class="k-table-index-column">#</th>
          <th data-mobile="true">Title</th>
          <th class="k-choice-rank">First</th>
          <th class="k-choice-rank">Second</th>
          <th class="k-choice-rank">Third</th>
          <th class="k-choice-rank" data-mobile="true">Total</th>
        </tr>
        <tr v-for="(choice, id, index) in choices" :key="id">
          <td data-mobile="true" class="k-table-index-column">{{ id == "zz_totals" ? '' : index + 1 }}</td>
          <td data-mobile="true">
            <details class="k-choices-stats-block">
              <summary class="k-choices-stats-title">{{ choice.title }}</summary>
              <div class="k-choices-stats-content">
                <dl class="k-choices-stats-list">
                  <template v-for="( year, key ) in choice.stats.years">
                    <dt class="k-choices-stats-list-item-title">{{ key }}</dt>
                    <dd class="k-choices-stats-list-item-detail">{{ year }}</dd>
                  </template>
                </dl>
                <dl class="k-choices-stats-list">
                  <template v-for="( gender, key ) in choice.stats.genders">
                    <dt class="k-choices-stats-list-item-title">{{ key }}</dt>
                    <dd class="k-choices-stats-list-item-detail">{{ gender }}</dd>
                  </template>
                </dl>
                <dl class="k-choices-stats-list">
                  <dt class="k-choices-stats-list-item-title">Average</dt>
                  <dd class="k-choices-stats-list-item-detail">{{ choice.stats.average }}</dd>
                </dl>
              </div>
            </details>
          </td>
          <td class="k-choice-rank">{{ choice.first }}</td>
          <td class="k-choice-rank">{{ choice.second }}</td>
          <td class="k-choice-rank">{{ choice.third }}</td>
          <td class="k-choice-rank" data-mobile="true">{{ choice.total }}</td>
        </tr>
      </table>
      </div>
    </div>
  </k-inside>
</template>

<script>
export default {
  props: {
    choices: Object
  },
  methods: {
  }
};
</script>


<style>
.k-choices-stats-list {
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin: 10px 0;
}
.k-choices-stats-list > * {
  padding-block: 5px;
  padding-inline-end: 10px;
  border-bottom: 1px solid #c0c0c0;
}
.k-choices-wrap {
  width: 100%;
  overflow-x: auto;
  overflow-y: hidden;
}
.k-choices {
  width: 100%;
  table-layout: fixed;
  border-spacing: 1px;
}
.k-choices td,
.k-choices th {
  text-align: left;
  font-size: var(--text-sm);
  padding: var(--spacing-2);
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  background: var(--color-white);
}
.k-choices :is( td, th ):not(.k-choice-rank) {
  width: 20rem;
}
.k-choices :is(td,th).k-table-index-column {
  width: var(--table-row-height);
}
.k-choice-rank {
  width: 8rem;
}
</style>