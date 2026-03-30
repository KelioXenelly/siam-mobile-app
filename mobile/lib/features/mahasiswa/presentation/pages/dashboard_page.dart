import 'package:flutter/material.dart';
import 'package:mobile/shared/widgets/glass_card.dart';
import 'package:mobile/shared/widgets/progress_ring.dart';
import 'package:mobile/shared/widgets/bottom_nav.dart';

class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {

  int _currentIndex = 0;

    void _onNavTapped(int index) {
      if (index == _currentIndex) return;

      switch (index) {
        case 0:
          Navigator.pushReplacementNamed(context, '/dashboard');
          break;

        case 1:
        // nanti scan
          break;

        case 2:
        // nanti riwayat
          break;

        case 3:
          Navigator.pushReplacementNamed(context, '/profile');
          break;
      }
    }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            // 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF2563EB), Color(0xFF3B82F6)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(30),
                  bottomRight: Radius.circular(30),
                ),
              ),
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Halo,", style: TextStyle(color: Colors.white70)),
                  SizedBox(height: 5),
                  Text(
                    "Kelio Xenelly",
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    "23110001 • Sistem Informasi",
                    style: TextStyle(color: Colors.white70),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // 🔥 CONTENT
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [

                  // 📊 STATS
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      _buildStatCard("Hadir", 2, Colors.green),
                      _buildStatCard("Terlambat", 1, Colors.orange),
                      _buildStatCard("Alfa", 0, Colors.red),
                    ],
                  ),

                  const SizedBox(height: 20),

                  // 📈 PROGRESS
                  const GlassCard(
                    child: Column(
                      children: [
                        SizedBox(height: 10),
                        ProgressRing(progress: 100),
                        SizedBox(height: 10),
                        Text("Tingkat kehadiran Anda sangat baik! 🎉"),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  // 📷 SCAN BUTTON
                  Container(
                    width: double.infinity,
                    height: 60,
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF2563EB), Color(0xFF4F46E5)],
                      ),
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.blue.withOpacity(0.3),
                          blurRadius: 10,
                        )
                      ],
                    ),
                    child: const Center(
                      child: Text(
                        "Scan QR Code 🔥",
                        style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),

                  const SizedBox(height: 20),

                  Container(
                    margin: const EdgeInsets.only(bottom: 20.0),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          "Absensi Terkini",
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        GestureDetector(
                          onTap: () {
                            Navigator.pushNamed(context, '/riwayat');
                          },
                          child: Row(
                            children: const [
                              Text(
                                "Lihat Semua",
                                style: TextStyle(
                                  color: Colors.blue,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                              SizedBox(width: 4),
                              Icon(Icons.arrow_forward, size: 16, color: Colors.blue),
                            ],
                          ),
                        )
                      ],
                    ),
                  ),

                  // 📋 RECENT
                  _buildRecentItem("Pemrograman Web", "Hadir"),
                  _buildRecentItem("Basis Data", "Hadir"),
                  _buildRecentItem("Algoritma", "Terlambat"),

                  const SizedBox(height: 80),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  // 🔹 STAT CARD
  Widget _buildStatCard(String title, int value, Color color) {
    return Expanded(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 4),
        child: GlassCard(
          child: Column(
            children: [
              Icon(Icons.circle, color: color, size: 14),
              const SizedBox(height: 6),
              Text(
                "$value",
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(title, style: const TextStyle(fontSize: 12)),
            ],
          ),
        ),
      ),
    );
  }

  // 🔹 RECENT ITEM
  Widget _buildRecentItem(String matkul, String status) {
    Color color = status == "Hadir"
        ? Colors.green
        : status == "Terlambat"
        ? Colors.orange
        : Colors.red;

    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: GlassCard(
        child: Row(
          children: [
            Icon(Icons.check_circle, color: color),
            const SizedBox(width: 10),
            Expanded(child: Text(matkul)),
            Text(
              status,
              style: TextStyle(color: color),
            ),
          ],
        ),
      ),
    );
  }
}

