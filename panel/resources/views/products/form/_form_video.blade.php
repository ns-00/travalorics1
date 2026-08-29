<div class="mb-3 col-12 col-md-8" id="product-video">
  <label class="form-label">{{ __('panel/product.video') }}</label>
  <div class="border rounded">
    <div class="nav nav-tabs video-nav-tabs" role="tablist">
      <button :class="['nav-link rounded-0', videoForm.videoType == 'local' ? 'active' : '']" @click="videoTypeChange('local')" data-bs-toggle="tab" data-bs-target="#nav-v-local" type="button">{{ __('panel/product.video_local') }}</button>
      <button :class="['nav-link rounded-0', videoForm.videoType == 'iframe' ? 'active' : '']" @click="videoTypeChange('iframe')" data-bs-toggle="tab" data-bs-target="#nav-v-iframe" type="button">{{ __('panel/product.video_iframe') }}</button>
      <button :class="['nav-link rounded-0', videoForm.videoType == 'custom' ? 'active' : '']" @click="videoTypeChange('custom')" data-bs-toggle="tab" data-bs-target="#nav-v-custom" type="button">{{ __('panel/product.video_custom') }}</button>
    </div>

    <div class="tab-content p-3" id="nav-tabContent">
      <div :class="['tab-pane fade', videoForm.videoType == 'local' ? 'show active' : '']" id="nav-v-local">
        <div class="d-flex align-items-end">
          <div class="set-product-img wh-80 rounded-2 me-2 border d-flex justify-content-center align-items-center cursor-pointer" @click="addProductVideo" style="background-color: #f8f9fa;" title="Click to select video file">
            <i v-if="videoForm.url" class="bi bi-play-circle fs-1 text-primary"></i>
            <i v-else class="bi bi-plus fs-1 text-muted"></i>
          </div>
          <div v-if="videoForm.url" class="video-actions">
            <a target="_blank" :href="videoForm.url" class="btn btn-sm btn-outline-primary mb-2"><i class="bi bi-eye me-1"></i>{{ __('panel/common.preview') }}</a>
            <button type="button" @click="deleteVideo" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>{{ __('panel/common.delete') }}</button>
          </div>
        </div>
        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>{{ __('panel/product.video_local_help') }}</div>
      </div>
      
      <div :class="['tab-pane fade', videoForm.videoType == 'iframe' ? 'show active' : '']" id="nav-v-iframe">
        <textarea class="form-control" rows="3" placeholder="{{ __('panel/product.video_iframe_placeholder') }}" v-model="videoForm.iframe"></textarea>
        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>{{ __('panel/product.video_iframe_help') }}</div>
      </div>
      
      <div :class="['tab-pane fade', videoForm.videoType == 'custom' ? 'show active' : '']" id="nav-v-custom">
        <input class="form-control" placeholder="{{ __('panel/product.video_custom_placeholder') }}" v-model="videoForm.custom">
        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>{{ __('panel/product.video_custom_help') }}</div>
      </div>
    </div>

    <input type="hidden" name="video" :value="videoForm.path">
  </div>
</div>

@push('footer')
<script>
  const videoApp = Vue.createApp({
    data() {
      return {
        videoForm: {
          videoType: 'local',
          url: '',
          iframe: '',
          custom: '',
          path: ''
        }
      };
    },
    mounted() {
      this.initVideoData();
    },
    watch: {
      'videoForm.videoType'() { this.updateVideoPath(); },
      'videoForm.url'() { this.updateVideoPath(); },
      'videoForm.iframe'() { this.updateVideoPath(); },
      'videoForm.custom'() { this.updateVideoPath(); }
    },
    methods: {
      initVideoData() {
        const existingVideo = @json(old('video', $product->video ?? ''));
        this.resetVideoForm();
        if (existingVideo) {
          if (typeof existingVideo === 'string') {
            try {
              const videoData = JSON.parse(existingVideo);
              this.setVideoDataFromObject(videoData);
            } catch (e) {
              this.videoForm.url = this.sanitizeString(existingVideo);
              this.videoForm.videoType = 'local';
            }
          } else if (typeof existingVideo === 'object' && existingVideo !== null) {
            this.setVideoDataFromObject(existingVideo);
          }
        }
        this.updateVideoPath();
      },
      resetVideoForm() {
        this.videoForm.videoType = 'local';
        this.videoForm.url = '';
        this.videoForm.iframe = '';
        this.videoForm.custom = '';
        this.videoForm.path = '';
      },
      setVideoDataFromObject(videoData) {
        if (videoData && typeof videoData === 'object') {
          this.videoForm.videoType = this.sanitizeVideoType(videoData.type);
          this.videoForm.url = this.sanitizeString(videoData.url);
          this.videoForm.iframe = this.sanitizeString(videoData.iframe);
          this.videoForm.custom = this.sanitizeString(videoData.custom);
        }
      },
      sanitizeVideoType(type) {
        const validTypes = ['local', 'iframe', 'custom'];
        return validTypes.includes(type) ? type : 'local';
      },
      sanitizeString(value) {
        if (typeof value === 'string') return value.trim();
        return '';
      },
      videoTypeChange(type) {
        this.videoForm.videoType = type;
      },
      addProductVideo() {
        const self = this;
        inno.fileManagerIframe(function(file) {
          if (file) {
            let videoPath = file.origin_url || file.url || file.path;
            if (videoPath) {
              videoPath = self.sanitizeString(videoPath).replace(/[`'"]/g, '').trim();
              self.videoForm.url = videoPath;
            }
          }
        }, { type: "video", multiple: false });
      },
      deleteVideo() {
        this.videoForm.url = '';
        this.videoForm.iframe = '';
        this.videoForm.custom = '';
        this.updateVideoPath();
      },
      updateVideoPath() {
        const currentValue = this.getCurrentVideoValue();
        if (currentValue && currentValue.trim()) {
          this.videoForm.path = JSON.stringify(this.buildVideoDataObject());
        } else {
          this.videoForm.path = '';
        }
      },
      getCurrentVideoValue() {
        switch (this.videoForm.videoType) {
          case 'local': return this.videoForm.url;
          case 'iframe': return this.videoForm.iframe;
          case 'custom': return this.videoForm.custom;
          default: return '';
        }
      },
      buildVideoDataObject() {
        const videoData = { type: this.videoForm.videoType };
        if (this.videoForm.url) videoData.url = this.videoForm.url.trim();
        if (this.videoForm.iframe) videoData.iframe = this.videoForm.iframe.trim();
        if (this.videoForm.custom) videoData.custom = this.videoForm.custom.trim();
        return videoData;
      }
    }
  });
  videoApp.mount('#product-video');
</script>
@endpush
