<section id="banner-slider" class="fluid_container">
                            <div class="camera_wrap camera_azure_skin" id="camera_wrap_0">
                                @imageSliderContent@
                            </div>
                        </section>
                        <script>
                    jQuery(function() {
                        jQuery('#camera_wrap_0').camera({
                            thumbnails: true,
                            loader: true,
                            hover: true,
                        });
                    });
                        </script>