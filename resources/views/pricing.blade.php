@extends('layouts.app')
@section('title', 'Pricing')

@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Pricing</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item">Pricing</div>
        </div>
      </div>

      <div class="section-body">
        <h2 class="section-title">Pricing Plans</h2>
        <p class="section-lead">Pilih paket yang sesuai dengan kebutuhan integrasi API Anda.</p>

        <div class="row">
          <div class="col-12 col-md-4 col-lg-4">
            <div class="pricing">
              <div class="pricing-title">
                Basic
              </div>
              <div class="pricing-padding">
                <div class="pricing-price">
                  <div>Free</div>
                  <div>per month</div>
                </div>
                <div class="pricing-details">
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">50 Checks per day</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Public API Access</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-danger text-white"><i class="fas fa-times"></i></div>
                    <div class="pricing-item-label">Unlimited Checks</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-danger text-white"><i class="fas fa-times"></i></div>
                    <div class="pricing-item-label">24/7 Support</div>
                  </div>
                </div>
              </div>
              <div class="pricing-cta">
                <a href="#">Current Plan <i class="fas fa-check"></i></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="pricing pricing-highlight">
              <div class="pricing-title">
                Pro
              </div>
              <div class="pricing-padding">
                <div class="pricing-price">
                  <div>$10</div>
                  <div>per month</div>
                </div>
                <div class="pricing-details">
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">1,000 Checks per day</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Public API Access</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Priority Support</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-danger text-white"><i class="fas fa-times"></i></div>
                    <div class="pricing-item-label">Unlimited Checks</div>
                  </div>
                </div>
              </div>
              <div class="pricing-cta">
                @if (auth()->user()->plan === 'pro' && auth()->user()->plan_expires_at && auth()->user()->plan_expires_at->isFuture())
                  <a href="#">Subscribed <i class="fas fa-check"></i></a>
                @else
                  <form action="{{ route('subscribe') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block p-3">Get Started <i class="fas fa-arrow-right"></i></button>
                  </form>
                @endif
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="pricing">
              <div class="pricing-title">
                Ultimate
              </div>
              <div class="pricing-padding">
                <div class="pricing-price">
                  <div>$25</div>
                  <div>per month</div>
                </div>
                <div class="pricing-details">
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Unlimited Checks</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Public API Access</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">Dedicated Account Manager</div>
                  </div>
                  <div class="pricing-item">
                    <div class="pricing-item-icon bg-success text-white"><i class="fas fa-check"></i></div>
                    <div class="pricing-item-label">24/7 Premium Support</div>
                  </div>
                </div>
              </div>
              <div class="pricing-cta">
                @if (auth()->user()->plan === 'pro' && auth()->user()->plan_expires_at && auth()->user()->plan_expires_at->isFuture())
                  <a href="#">Plan Upgrade Available <i class="fas fa-arrow-up"></i></a>
                @else
                  <form action="{{ route('subscribe') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block p-3">Get Started <i class="fas fa-arrow-right"></i></button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection


@push('styles')
  <style>
    .pricing {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
      background-color: #fff;
      border-radius: 3px;
      border: none;
      position: relative;
      margin-bottom: 30px;
      text-align: center;
    }

    .pricing.pricing-highlight {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      z-index: 2;
    }

    .pricing.pricing-highlight .pricing-title {
      background-color: #6777ef;
      color: #fff;
    }

    .pricing .pricing-title {
      padding: 20px;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 700;
      background-color: #f9f9f9;
      color: #191d21;
      border-radius: 3px 3px 0 0;
    }

    .pricing .pricing-padding {
      padding: 40px;
    }

    .pricing .pricing-price {
      margin-bottom: 30px;
    }

    .pricing .pricing-price div:first-child {
      font-size: 50px;
      font-weight: 700;
      color: #6777ef;
    }

    .pricing .pricing-details {
      text-align: left;
      display: inline-block;
    }

    .pricing .pricing-details .pricing-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .pricing .pricing-details .pricing-item .pricing-item-icon {
      width: 20px;
      height: 20px;
      line-height: 20px;
      border-radius: 50%;
      text-align: center;
      font-size: 10px;
      margin-right: 15px;
    }

    .pricing .pricing-cta {
      background-color: #f9f9f9;
      border-radius: 0 0 3px 3px;
    }

    .pricing .pricing-cta a {
      display: block;
      padding: 20px;
      text-transform: uppercase;
      font-weight: 700;
      text-decoration: none;
      letter-spacing: 1px;
    }

    .pricing .pricing-cta a:hover {
      background-color: #eee;
    }
  </style>
@endpush
