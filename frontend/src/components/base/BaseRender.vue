<script>
export default {
  props: ['renderEl', 'params'],
  render: function(h) {
    return this.renderEl(h, this.params);
  },
};
</script>
