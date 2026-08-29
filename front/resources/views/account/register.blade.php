@extends('layouts.app')
@section('body-class', 'page-login')
@section('content')
  @if (!request('iframe'))
    <x-front-breadcrumb type="route" value="register.index" title="{{ __('front/account.register') }}" />
  @endif
  @push('header')
    <style>
      .auth-container {
        max-width: 480px;
        margin: 60px auto;
      }

      .premium-auth-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        border-radius: 24px;
        padding: 40px;
      }

      .premium-auth-box.iframe {
        box-shadow: none;
        margin: 0;
        padding: 20px;
        border: none;
        border-radius: 0;
        background: #fff;
      }

      .auth-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 8px;
        text-align: center;
      }

      .auth-sub-title {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 35px;
        text-align: center;
      }

      .auth-input {
        border-radius: 12px;
        padding: 15px 20px;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
        font-size: 1rem;
        transition: all 0.3s ease;
      }

      .auth-input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
      }

      .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
        color: #fff;
        border-radius: 12px;
        padding: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(27, 77, 62, 0.4);
        color: #fff;
      }

      .auth-links {
        margin-top: 25px;
        text-align: center;
        font-size: 0.95rem;
      }

      .auth-links a {
        color: var(--primary);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
      }

      .auth-links a:hover {
        color: #2C6E58;
        text-decoration: underline;
      }
    </style>
  @endpush
  @hookinsert('account.register.top')
  <div class="container">
    <div class="auth-container">
      <div class="premium-auth-box {{ request('iframe') ? 'iframe' : '' }}">
        <div class="auth-title">{{ __('front/register.register') }}</div>
        <div class="auth-sub-title">{{ __('front/register.register_text') }}</div>

        <form action="{{ front_route('register.store') }}" class="needs-validation form-wrap" novalidate>
          @csrf
          @hookupdate('account.register.email')
          <div class="form-group mb-4">
            <input id="email" type="email" class="form-control auth-input" name="email" value="{{ old('email') }}"
              required autocomplete="email" placeholder="{{ __('front/login.email') }}" />
            <span class="invalid-feedback" role="alert"><strong>{{ __('front/login.email_required') }}</strong></span>
          </div>
          @endhookupdate
          @hookinsert('account.register.email.after')

          <div class="form-group mb-4">
            <input id="password" type="password" class="form-control auth-input" name="password" required
              autocomplete="new-password" placeholder="{{ __('front/login.password') }}" />
            <input class="d-none" name="password_confirmation" />
            <span class="invalid-feedback" role="alert"><strong>{{ __('front/login.password_required') }}</strong></span>
          </div>
          <button type="button" class="btn btn-gradient-primary w-100 form-submit shadow-sm mb-3">
            <i class="bi bi-person-plus-fill me-2"></i>{{ __('front/register.register_submit') }}
          </button>

          <div class="auth-links">
            <span class="text-muted">{{ __('front/register.have_account') }}</span>
            <a href="{{ front_route('login.index') }}{{ request('iframe') ? '?iframe=true' : '' }}">
              {{ __('front/login.login') }} <i class="bi bi-arrow-right-short align-middle"></i>
            </a>
          </div>
          <div class="mt-4 pt-3 border-top">
            @include('account/_social')
          </div>
        </form>
      </div>
    </div>
  </div>
  @hookinsert('account.register.bottom')
@endsection
@push('footer')
  <script>
    const iframe = @json(request('iframe', false));
    inno.validateAndSubmitForm('.form-wrap', function (data) {
      layer.load(2, { shade: [0.3, '#fff'] })
      const params = new URLSearchParams(data);
      params.set('password_confirmation', $('input[name="password"]').val());
      axios.post($('.form-wrap').attr('action'), params.toString()).then(function (res) {
        if (res.success) {
          if (res.require_otp) {
            layer.closeAll('loading');
            layer.prompt({
              title: 'أدخل رمز التحقق (OTP) المرسل لبريدك',
              formType: 0,
              btn: ['تأكيد', 'إلغاء']
            }, function(otp, index){
              layer.close(index);
              layer.load(2, { shade: [0.3, '#fff'] });
              axios.post('{{ front_route('register.verify') }}', { email: res.email, otp: otp })
                .then(function(verifyRes) {
                  if (verifyRes.success) {
                    if (iframe) {
                      setTimeout(() => {
                        parent.layer.closeAll()
                        parent.window.location.reload()
                      }, 400);
                    } else {
                      inno.msg(verifyRes.message || 'تم التسجيل بنجاح!', { icon: 1 });
                      location.href = '{{ front_route('account.index') }}';
                    }
                  } else {
                    inno.msg(verifyRes.message, { icon: 2 });
                  }
                }).finally(function () { layer.closeAll('loading') });
            });
          } else {
            if (iframe) {
              setTimeout(() => {
                parent.layer.closeAll()
                parent.window.location.reload()
              }, 400);
            } else {
              inno.msg(res.message, { icon: 1 });
              location.href = '{{ front_route('account.index') }}';
            }
          }
        } else {
          inno.msg(res.message, { icon: 2 });
        }
      }).finally(function () { layer.closeAll('loading') });
    });
  </script>
@endpush
